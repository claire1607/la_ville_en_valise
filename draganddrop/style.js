(function dragndrop() {
    let xpos = '';
    let ypos = '';
    let whichArt = '';
  
    function resetZ() {
      const imgEl = document.querySelectorAll('img');
      for (let i = imgEl.length - 1; i >= 0; i--) {
        imgEl[i].style.zIndex = 5;
      }
    }
    
    function moveStart(e) {
      whichArt = e.target;
      xpos = e.offsetX === undefined ? e.layerX : e.offsetX;
      ypos = e.offsetY === undefined ? e.layerY : e.offsetY;
      whichArt.style.zIndex = 10;
    }
  
    function moveDragOver(e) {
      e.preventDefault();
    }
  
    function moveDrop(e) {
      e.preventDefault();
      whichArt.style.left = e.pageX - xpos + 'px';
      whichArt.style.top = e.pageY - ypos + 'px';
    }
    
    function touchStart(e) {
      e.preventDefault();
      const whichArt = e.target;
      const touch = e.touches[0];
      let moveOffsetX = whichArt.offsetLeft - touch.pageX;
      let moveOffsetY = whichArt.offsetTop - touch.pageY;
      resetZ();
      whichArt.style.zIndex = 10;
  
      whichArt.addEventListener('touchmove', function() {
        let posX = touch.pageX + moveOffsetX;
        let posY = touch.pageY + moveOffsetY;
        whichArt.style.left = posX + 'px';
        whichArt.style.top = posY + 'px';
      }, false);
    }
  
    document.querySelector('body').addEventListener('dragstart', moveStart, false);
    document.querySelector('body').addEventListener('dragover', moveDragOver, false);
    document.querySelector('body').addEventListener('drop', moveDrop, false);
    
    document.querySelector('body').addEventListener('touchstart', touchStart, false);
  
  })();