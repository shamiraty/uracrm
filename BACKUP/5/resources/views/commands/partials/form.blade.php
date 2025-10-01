<!-- resources/views/commands/partials/form.blade.php -->
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" name="name" id="name" value="{{ $command->name ?? old('name') }}" required>
</div>
<div class="mb-3">
    <label for="region_id" class="form-label">Region</label>
    <select class="form-select" name="region_id" id="region_id" required>
        @foreach ($regions as $region)
            <option value="{{ $region->id }}" {{ isset($command) && $command->region_id == $region->id ? 'selected' : '' }}>
                {{ $region->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="branch_id" class="form-label">Branch</label>
    <select class="form-select" name="branch_id" id="branch_id" required>
        @foreach ($branches as $branch)
            <option value="{{ $branch->id }}" {{ isset($command) && $command->branch_id == $branch->id ? 'selected' : '' }}>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="district_id" class="form-label">District</label>
    <select class="form-select" name="district_id" id="district_id" required>
        @foreach ($districts as $district)
            <option value="{{ $district->id }}" {{ isset($command) && $command->district_id == $district->id ? 'selected' : '' }}>
                {{ $district->name }}
            </option>
        @endforeach
    </select>
</div>
