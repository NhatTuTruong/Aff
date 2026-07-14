<style>
.btn-get-code.btn-peel-sticker {
    --peel-primary: #7c3aed;
    --peel-primary-light: #ddd6fe;
    --peel-primary-dark: #6d28d9;
    position: relative;
    display: inline-flex;
    padding: 0;
    border: none;
    background: transparent;
    cursor: pointer;
    font-family: inherit;
    flex-shrink: 0;
    vertical-align: middle;
}
.btn-peel-sticker .peel-inner {
    position: relative;
    display: block;
    min-width: 148px;
    height: 40px;
    border: 2px dashed var(--primary-dark);
    border-radius: 6px;
    background: #fff;
    transition: box-shadow 0.2s ease;
}
.btn-peel-sticker .peel-reveal {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--primary-dark);
    letter-spacing: 0.08em;
    z-index: 0;
    pointer-events: none;
    user-select: none;
}
.btn-peel-sticker .peel-sheet {
    position: absolute;
    left: -3px;
    top: -3px;
    bottom: -3px;
    height: 42px;
    width: calc(100% - 15px);
    background: var(--primary-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 100%, 0 100%);
    border-radius: 4px 0 0 4px;
    transition: width 0.2s ease, background 0.2s ease;
}

.btn-peel-sticker:hover .peel-sheet {
    width: calc(100% - 20px);
}
.btn-peel-sticker .peel-text {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    letter-spacing: 0.03em;
}
.btn-peel-sticker .peel-flap {
    position: absolute;
    right: -1px;
    top: -1px;
    width: 38px;
    height: 38px;
    z-index: 2;
    pointer-events: none;
    background: linear-gradient(
        225deg,
        #ede9fe 0%,
        #ddd6fe 42%,
        var(--peel-primary-light) 43%,
        var(--primary-dark) 44%
    );
    clip-path: polygon(100% 0, 0 0, 100% 100%);
}
.btn-peel-sticker:hover .peel-inner {
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.32);
}
.btn-peel-sticker:active .peel-inner {
    transform: scale(0.98);
}
.btn-peel-sticker .peel-inner--deal .peel-sheet,
.peel-inner.peel-inner--deal .peel-sheet {
    width: calc(100% + 6px);
    clip-path: none;
    border-radius: 4px;
}
.btn-peel-sticker .peel-inner--deal .peel-reveal,
.btn-peel-sticker .peel-inner--deal .peel-flap,
.peel-inner.peel-inner--deal .peel-reveal,
.peel-inner.peel-inner--deal .peel-flap {
    display: none;
}
.btn-peel-sticker.btn-peel-sticker--wide .peel-inner {
    width: 100%;
    min-width: 0;
}
</style>
