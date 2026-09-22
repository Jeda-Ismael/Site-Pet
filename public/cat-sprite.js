document.addEventListener('DOMContentLoaded', () => {
  const viewport = document.getElementById('heroCatSprite');
  const canvas = document.getElementById('heroCatCanvas');
  if (!viewport || !canvas) return;

  const ctx = canvas.getContext('2d');
  const GRID_COLS = 8;
  const GRID_ROWS = 4;
  const FRAME_COUNT = GRID_COLS * GRID_ROWS;
  const FPS = 6;

  let frameIndex = 0;
  let direction = 1; // ida e volta (ping-pong) pela sprite sheet inteira
  let containerWidth = 0;
  let containerHeight = 0;
  let imageReady = false;
  let frameW = 0;
  let frameH = 0;

  const img = new Image();
  img.src = 'gato-sprite.webp';
  img.onload = () => {
    frameW = img.naturalWidth / GRID_COLS;
    frameH = img.naturalHeight / GRID_ROWS;
    imageReady = true;
    draw();
  };

  // Só lê o layout (getBoundingClientRect) aqui, nunca dentro do loop de
  // animação — evita forçar recálculo de layout a cada frame.
  function measure() {
    const rect = viewport.getBoundingClientRect();
    containerWidth = rect.width;
    containerHeight = rect.height;

    // limitado a 1.5: acima disso o ganho de nitidez é marginal, mas o custo de
    // desenhar cada frame cresce ao quadrado (ex.: dpr 2 = 4x mais pixels por frame)
    const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
    canvas.width = Math.round(containerWidth * dpr);
    canvas.height = Math.round(containerHeight * dpr);
    canvas.style.width = `${containerWidth}px`;
    canvas.style.height = `${containerHeight}px`;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

    draw();
  }

  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(measure, 150);
  });

  function draw() {
    if (!imageReady || containerWidth === 0) return;
    const col = frameIndex % GRID_COLS;
    const row = Math.floor(frameIndex / GRID_COLS);
    ctx.clearRect(0, 0, containerWidth, containerHeight);
    ctx.drawImage(
      img,
      col * frameW, row * frameH, frameW, frameH,
      0, 0, containerWidth, containerHeight
    );
  }

  function tick() {
    frameIndex += direction;
    if (frameIndex >= FRAME_COUNT - 1) {
      frameIndex = FRAME_COUNT - 1;
      direction = -1;
    } else if (frameIndex <= 0) {
      frameIndex = 0;
      direction = 1;
    }
    draw();
  }

  measure();
  setInterval(tick, 1000 / FPS);
});
