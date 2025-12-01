// public/assets/js/app.js
(function(){
    function updateNotiCount(){
        const uid = document.body.getAttribute('data-userid');
        if (!uid) return;
        fetch('../controllers/notificaciones.php?list=1')
            .then(r=>r.json())
            .then(data=>{
                const el = document.getElementById('noti-count');
                if (el) el.textContent = data.length;
            }).catch(()=>{});
    }
    setInterval(updateNotiCount, 5000);
    updateNotiCount();

    const link = document.getElementById('noti-link');
    if (link) link.addEventListener('click', function(e){
        e.preventDefault();
        fetch('../controllers/notificaciones.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body: 'mark_read=1'
        }).then(()=> updateNotiCount()).catch(()=>{});
        alert("Buzón marcado como leído.");
    });
})();
