document.addEventListener('DOMContentLoaded', () => {
  const viewport = document.getElementById('heroCatSprite');
  const canvas = document.getElementById('heroCatCanvas');
  if (!viewport || !canvas) return;

  const ctx = canvas.getContext('2d');
  const GRID_COLS = 8;
  const GRID_ROWS = 4;
  const FRAME_COUNT = GRID_COLS * GRID_ROWS;
  const IDLE_FRAME = 0;
  const EASE = 0.05;

  let currentFrame = IDLE_FRAME;
  let targetFrame = IDLE_FRAME;
  let containerWidth = 0;
  let containerHeight = 0;
  let centerX = 0;
  let lastMouseX = null;
  let lastDrawnFrame = -1;
  let imageReady = false;
  let frameW = 0;
  let frameH = 0;

  const img = new Image();
  img.src = 'gato-sprite.webp';
  img.onload = () => {
    frameW = img.naturalWidth / GRID_COLS;
    frameH = img.naturalHeight / GRID_ROWS;
    imageReady = true;
  };

  // Só lê o layout (getBoundingClientRect) aqui, nunca dentro do mousemove nem do
  // loop de animação — misturar leituras de layout com os desenhos a cada frame
  // força o navegador a recalcular o layout repetidamente ("layout thrashing"),
  // e era a causa real da travada.
  function measure() {
    const rect = viewport.getBoundingClientRect();
    containerWidth = rect.width;
    containerHeight = rect.height;
    centerX = rect.left + rect.width / 2;

    const dpr = window.devicePixelRatio || 1;
    canvas.width = Math.round(containerWidth * dpr);
    canvas.height = Math.round(containerHeight * dpr);
    canvas.style.width = `${containerWidth}px`;
    canvas.style.height = `${containerHeight}px`;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

    lastDrawnFrame = -1; // força redesenho no próximo tick
  }

  function targetFromMouseX(clientX) {
    // segue o mouse pela página inteira, não só quando está sobre o elemento
    const range = Math.max(window.innerWidth / 2, 1);
    const offset = clientX - centerX;
    const t = Math.min(1, Math.max(0, (offset + range) / (range * 2)));
    return t * (FRAME_COUNT - 1);
  }

  // O mousemove só guarda um número (nada de leitura de layout aqui) —
  // pode disparar centenas de vezes por segundo sem custo nenhum.
  window.addEventListener('mousemove', (e) => {
    lastMouseX = e.clientX;
  }, { passive: true });

  document.addEventListener('mouseleave', () => {
    lastMouseX = null;
  });

  window.addEventListener('blur', () => {
    lastMouseX = null;
  });

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(measure, 150);
  });

  function render() {
    if (lastMouseX !== null) {
      targetFrame = targetFromMouseX(lastMouseX);
    } else {
      targetFrame = IDLE_FRAME;
    }

    currentFrame += (targetFrame - currentFrame) * EASE;

    if (imageReady && containerWidth > 0) {
      const displayFrame = Math.round(currentFrame);
      if (displayFrame !== lastDrawnFrame) {
        const col = displayFrame % GRID_COLS;
        const row = Math.floor(displayFrame / GRID_COLS);
        ctx.clearRect(0, 0, containerWidth, containerHeight);
        ctx.drawImage(
          img,
          col * frameW, row * frameH, frameW, frameH,
          0, 0, containerWidth, containerHeight
        );
        lastDrawnFrame = displayFrame;
      }
    }

    requestAnimationFrame(render);
  }

  measure();
  render();
});
