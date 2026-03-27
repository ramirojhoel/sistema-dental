@if(session('success') || session('error'))
<div id="toast"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl text-sm font-semibold transition-all duration-500"
    style="{{ session('success') ? 'background:#22c55e; color:white;' : 'background:#ef4444; color:white;' }}">
    <span class="text-lg">{{ session('success') ? '✅' : '❌' }}</span>
    <span>{{ session('success') ?? session('error') }}</span>
    <button onclick="closeToast()" style="margin-left:8px; opacity:0.7; font-size:18px;">&times;</button>
</div>
<script>
    function closeToast() {
        var t = document.getElementById('toast');
        t.style.opacity = '0';
        setTimeout(function(){ t.remove(); }, 500);
    }
    setTimeout(closeToast, 3500);
</script>
@endif