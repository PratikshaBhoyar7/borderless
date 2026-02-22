@extends('layouts.admin')

@section('page-title', 'Known Conditions Management')

@section('admin-content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Known Conditions List</h4>

            <a href="{{ route('admin.known-conditions.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Known Condition
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">SR No</th>
                            <th>Title</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($knownConditions as $key => $condition)
                            <tr>
                                <td>{{ ($knownConditions->currentPage() - 1) * 10 + $key + 1 }}</td>
                                <td>{{ $condition->title }}</td>
                                <td>

                                    <a href="{{ route('admin.known-conditions.edit', $condition->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.known-conditions.destroy', $condition->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    No known conditions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($knownConditions->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $knownConditions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection