// Sprite loading
window.SPRITES = window.SPRITES || {};

function loadSprites() {
    const spriteNames = [
        'sprt_moai_back',
        'sprt_moai_front',
        'sprt_moai_left',
        'sprt_moai_right'
    ];
    
    spriteNames.forEach(name => {
        const img = new Image();
        img.src = 'sprites/moai/' + name + '.png';
        window.SPRITES[name] = img;
    });
}

// Auto-load sprites
loadSprites();
