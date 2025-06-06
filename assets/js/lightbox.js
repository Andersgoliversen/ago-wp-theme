(function(){
    var links=document.querySelectorAll('.gallery a');
    if(!links.length)return;
    var o=document.createElement('div');
    o.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:9999;';
    o.innerHTML='<img style="max-height:100%;max-width:100%" />';
    o.hidden=true;
    document.body.appendChild(o);
    var img=o.querySelector('img');
    links.forEach(function(a){
        a.addEventListener('click',function(e){
            e.preventDefault();
            img.src=a.href;
            o.hidden=false;
        });
    });
    o.addEventListener('click',function(){o.hidden=true;});
})();
