{{-- resources/views/admin/partials/footer.blade.php --}}
<footer class="admin-footer">
  {{-- Left: SkinQuo Logo --}}
  <div style="display:flex; align-items:center; gap:8px;">
    <img src="{{ asset('images/logo_skinquo_cream.png') }}"
         alt="SkinQuo" style="height:40px; width:auto; object-fit:contain;" />
    <span style="font-family:'Jost', sans-serif; font-weight:600;
                 font-size:18px; color:#F5E6D0; letter-spacing:0.04em;">
      SkinQuo
    </span>
  </div>

  {{-- Center: Copyright --}}
  <div style="font-family:'Jost'; font-size:13px; color:#C4A882; letter-spacing:0.04em;">
    &copy; 2026 SkinQuo &mdash; Copyright Reserved
  </div>

  {{-- Right: Social Icons --}}
  <div style="display:flex; gap:18px; align-items:center;">
    <a href="#" style="color:#F5E6D0; font-size:20px; text-decoration:none;">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="#" style="color:#F5E6D0; font-size:20px; text-decoration:none;">
      <i class="bi bi-facebook"></i>
    </a>
  </div>
</footer>