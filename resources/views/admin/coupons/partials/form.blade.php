<div class="mb-3">
    <label for="code" class="form-label">كود الخصم</label>
    <input type="text" class="form-control" name="code" value="{{ old('code', $coupon->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="discount_percentage" class="form-label">نسبة الخصم</label>
    <input type="number" class="form-control" name="discount_percentage" value="{{ old('discount_percentage', $coupon->discount_percentage ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="max_discount" class="form-label">الحد الأقصى للخصم</label>
    <input type="number" class="form-control" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
</div>
<div class="mb-3">
    <label for="expires_at" class="form-label">تاريخ الانتهاء</label>
    <input type="date" class="form-control" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ?? '') }}" required>
</div>
