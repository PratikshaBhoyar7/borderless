@extends('layouts.admin')

@section('page-title', 'Add Village')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-houses"></i> Add Village
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            @php
                $isStandaloneCreate = request()->route()->getName() === 'admin.villages.create';
                $formAction = $isStandaloneCreate ? route('admin.villages.store') : route('admin.talukas.villages.store', $taluka);
            @endphp
            <form method="POST" action="{{ $formAction }}">
                @csrf

                <div class="mb-3">
                    <label for="taluka_id" class="form-label">Taluka <span style="color: red;">*</span></label>
                    <select class="form-select @error('taluka_id') is-invalid @enderror" id="taluka_id" name="taluka_id" required>
                        <option value="">-- Select Taluka --</option>
                        @foreach ($talukas as $t)
                            <option value="{{ $t->id }}" {{ old('taluka_id', $taluka?->id) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('taluka_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Village Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $taluka ? true : false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Create Village
                    </button>
                    <a href="{{ $isStandaloneCreate ? route('admin.villages.index') : route('admin.talukas.villages.index', $taluka) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
