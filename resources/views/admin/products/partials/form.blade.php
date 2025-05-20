<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label fw-semibold">اسم المنتج</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="category_id" class="form-label fw-semibold">الفئة</label>
        <select class="form-select @error('category_id') is-invalid @enderror" 
                id="category_id" name="category_id" required>
            <option value="">اختر الفئة</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-4 mb-3">
        <label for="quantity" class="form-label fw-semibold">الكمية</label>
        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
               id="quantity" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" min="0" required>
        @error('quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-4 mb-3">
        <label for="price" class="form-label fw-semibold">السعر</label>
        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
               id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" required>
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-4 mb-3">
        <label for="offer" class="form-label fw-semibold">العرض</label>
        <div class="input-group">
            <input type="number" step="0.01" class="form-control @error('offer') is-invalid @enderror" 
                   id="offer" name="offer" value="{{ old('offer', $product->offer ?? '') }}" min="0">
            <select class="form-select @error('is_percent') is-invalid @enderror" 
                    id="is_percent" name="is_percent" style="max-width: 100px;">
                <option value="0" {{ old('is_percent', $product->is_percent ?? 0) == 0 ? 'selected' : '' }}>جنيه</option>
                <option value="1" {{ old('is_percent', $product->is_percent ?? 0) == 1 ? 'selected' : '' }}>%</option>
            </select>
        </div>
        @error('offer')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="image" class="form-label fw-semibold">صورة المنتج</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" 
               id="image" name="image">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @isset($product->image)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$product->image) }}" alt="صورة المنتج" width="100">
            </div>
        @endisset
    </div>
    
    
    
    <div class="col-md-12 mb-3">
        <label for="description" class="form-label fw-semibold">الوصف</label>
        <textarea class="form-control @error('description') is-invalid @enderror" 
                  id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>