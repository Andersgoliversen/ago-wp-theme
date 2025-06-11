(function(){
  const links = Array.from(document.querySelectorAll('.gallery a'));
  if(!links.length) return;

  const overlay = document.createElement('div');
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);display:flex;align-items:center;justify-content:center;flex-direction:column;z-index:9999;';
  overlay.hidden = true;
  overlay.innerHTML = `
    <button class="lb-prev" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:2rem;color:white;background:none;border:none;cursor:pointer">\u276E</button>
    <img style="max-height:80vh;max-width:90vw;margin-bottom:.5rem" />
    <div class="lb-caption" style="color:white;text-align:center"></div>
    <button class="lb-next" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);font-size:2rem;color:white;background:none;border:none;cursor:pointer">\u276F</button>
  `;
  document.body.appendChild(overlay);

  const img = overlay.querySelector('img');
  const caption = overlay.querySelector('.lb-caption');
  const prevBtn = overlay.querySelector('.lb-prev');
  const nextBtn = overlay.querySelector('.lb-next');

  let index = 0;

  function getCaption(link){
    const fig = link.closest('figure');
    const figcap = fig ? fig.querySelector('figcaption') : null;
    return figcap ? figcap.textContent : (link.title || link.querySelector('img')?.alt || '');
  }

  function show(i){
    index = (i + links.length) % links.length;
    const link = links[index];
    img.src = link.href;
    caption.textContent = getCaption(link);
    overlay.hidden = false;
  }

  links.forEach((link, i) => {
    link.addEventListener('click', e => {
      e.preventDefault();
      show(i);
    });
  });

  function next(){ show(index + 1); }
  function prev(){ show(index - 1); }
  function close(){ overlay.hidden = true; }

  nextBtn.addEventListener('click', next);
  prevBtn.addEventListener('click', prev);
  overlay.addEventListener('click', e => { if(e.target === overlay) close(); });
  document.addEventListener('keydown', e => {
    if(overlay.hidden) return;
    if(e.key === 'ArrowRight') next();
    else if(e.key === 'ArrowLeft') prev();
    else if(e.key === 'Escape') close();
  });
})();
