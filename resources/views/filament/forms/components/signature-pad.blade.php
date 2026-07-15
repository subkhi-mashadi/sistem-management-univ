@php
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        wire:ignore
        x-data="{
            state: $wire.$entangle('{{ $statePath }}'),
            ctx: null,
            drawing: false,
            hasContent: false,
            init() {
                const canvas = this.$refs.canvas;
                canvas.width = canvas.offsetWidth;
                canvas.height = 180;
                this.ctx = canvas.getContext('2d');
                this.ctx.strokeStyle = '#111827';
                this.ctx.lineWidth = 2.5;
                this.ctx.lineCap = 'round';
                if (this.state) {
                    this.drawImageFromDataUrl(this.state);
                    this.hasContent = true;
                }
            },
            drawImageFromDataUrl(dataUrl) {
                const img = new Image();
                img.onload = () => {
                    this.ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
                    this.ctx.drawImage(img, 0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
                };
                img.src = dataUrl;
            },
            pos(e) {
                const rect = this.$refs.canvas.getBoundingClientRect();
                const t = e.touches ? e.touches[0] : e;
                return { x: t.clientX - rect.left, y: t.clientY - rect.top };
            },
            start(e) {
                this.drawing = true;
                const p = this.pos(e);
                this.ctx.beginPath();
                this.ctx.moveTo(p.x, p.y);
            },
            move(e) {
                if (!this.drawing) return;
                const p = this.pos(e);
                this.ctx.lineTo(p.x, p.y);
                this.ctx.stroke();
            },
            end() {
                if (!this.drawing) return;
                this.drawing = false;
                this.hasContent = true;
                this.state = this.$refs.canvas.toDataURL('image/png');
            },
            clear() {
                this.ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
                this.hasContent = false;
                this.state = null;
            },
            upload(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    this.drawImageFromDataUrl(ev.target.result);
                    this.hasContent = true;
                    this.state = ev.target.result;
                };
                reader.readAsDataURL(file);
                e.target.value = '';
            },
        }"
        class="space-y-2"
    >
        <canvas
            x-ref="canvas"
            x-on:mousedown="start($event)"
            x-on:mousemove="move($event)"
            x-on:mouseup="end()"
            x-on:mouseleave="end()"
            x-on:touchstart.prevent="start($event)"
            x-on:touchmove.prevent="move($event)"
            x-on:touchend.prevent="end()"
            style="width:100%;height:180px;border:1px dashed #9ca3af;border-radius:0.5rem;background:#fff;touch-action:none;cursor:crosshair;"
        ></canvas>

        <div class="flex items-center gap-2">
            <button
                type="button"
                x-on:click="clear()"
                class="fi-btn fi-btn-size-sm fi-color-gray fi-btn-color-gray inline-flex items-center gap-1 rounded-lg border px-3 py-1.5 text-sm font-medium"
            >
                Hapus
            </button>

            <label class="fi-btn fi-btn-size-sm fi-color-gray fi-btn-color-gray inline-flex cursor-pointer items-center gap-1 rounded-lg border px-3 py-1.5 text-sm font-medium">
                Upload Gambar
                <input type="file" accept="image/*" class="hidden" x-on:change="upload($event)">
            </label>

            <span class="text-xs text-gray-500" x-show="!hasContent">Gambar langsung di atas, atau upload file gambar TTD.</span>
        </div>
    </div>
</x-dynamic-component>
