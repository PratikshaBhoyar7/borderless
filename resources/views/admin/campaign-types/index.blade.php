@extends('layouts.admin')

@section('page-title', 'Campaign Types Management')

@section('admin-content')

<!-- Header with Add Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-collection"></i> Campaign Types List 
    </h2>
    <a href="{{ route('admin.campaign-types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill"></i> Add New Campaign Type
    </a>
</div>

<!-- Campaign Types Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Campaign Types List</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name..." onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $campaignTypes->count() }}</strong> records
        </p>
    </div>

    @if ($campaignTypes->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaignTypes as $campaignType)
                        <tr>
                            <td><strong>{{ $campaignType->name }}</strong></td>
                            <td>
                                <small>
                                    {{ $campaignType->description ? substr($campaignType->description, 0, 50) . (strlen($campaignType->description) > 50 ? '...' : '') : 'N/A' }}
                                </small>
                            </td>
                            <td>
                                @if($campaignType->deleted_at)
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    @if($campaignType->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $campaignType->created_at->format('M d, Y') }}</small></td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @if($campaignType->deleted_at)
                                        <form method="POST" action="{{ route('admin.campaign-types.restore', $campaignType) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-edit" title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.campaign-types.edit', $campaignType) }}" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $campaignType->id }}" title="Delete">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $campaignType->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header bg-danger text-white border-0">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">
                                                            Are you sure you want to delete <strong>{{ $campaignType->name }}</strong>?
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="{{ route('admin.campaign-types.destroy', $campaignType) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No campaign types found.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if ($campaignTypes->hasPages())
        <div class="pagination-container mt-3">
            {{ $campaignTypes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script>
function searchRecords() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.querySelector('.records-table');
    const rows = table.querySelectorAll('tbody tr');
    let count = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('recordsCount').textContent = count;
}
</script>

@endsection
