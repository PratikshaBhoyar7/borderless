@extends('layouts.admin')

@section('page-title', 'Diagnosis Management')

@section('admin-content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Diagnosis List</h4>

            <a href="{{ route('admin.diagnoses.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Diagnosis
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">SR No</th>
                            <th>Diagnosis Title</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnoses as $key => $diagnosis)
                            <tr>
                                <td>{{ ($diagnoses->currentPage() - 1) * 10 + $key + 1 }}</td>
                                <td>{{ $diagnosis->title }}</td>
                                <td>
                                    <a href="{{ route('admin.diagnoses.edit', $diagnosis->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.diagnoses.destroy', $diagnosis->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this diagnosis?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    No diagnoses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($diagnoses->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $diagnoses->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection