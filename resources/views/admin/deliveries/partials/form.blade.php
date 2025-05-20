<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label fw-semibold">اسم عامل التوصيل</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name', $delivery->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email', $delivery->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label fw-semibold">رقم الهاتف</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
               id="phone" name="phone" value="{{ old('phone', $delivery->phone ?? '') }}" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="password" class="form-label fw-semibold">كلمة المرور</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" {{ isset($delivery) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
