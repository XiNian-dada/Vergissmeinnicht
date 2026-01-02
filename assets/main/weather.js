/**
 * High Performance Weather System v4.0 (Final)
 * 优化：白天雨滴颜色自动变灰蓝，解决看不清和生硬的问题
 * 优化：乌云压顶逻辑改为 CSS 遮罩层控制
 */
const WeatherSystem = {
    canvas: null,
    ctx: null,
    width: 0,
    height: 0,
    particles: [],
    animationId: null,
    currentType: 'none', 
    container: null,

    init: function() {
        this.container = document.getElementById('weather-container');
        if (this.container) {
            // 确保容器在最底层且不阻挡点击
            this.container.style.pointerEvents = 'none';
            this.container.style.zIndex = '0'; // 配合 CSS，稍微提一点层级以便遮罩生效，但在内容之下
        }

        this.canvas = document.getElementById('weather-canvas');
        if (!this.canvas || !this.container) return;

        this.ctx = this.canvas.getContext('2d');
        this.resize();
        window.addEventListener('resize', () => this.resize());

        const savedWeather = localStorage.getItem('weather') || 'none';
        this.setWeather(savedWeather);
    },

    resize: function() {
        if (!this.container) return;
        this.width = this.container.offsetWidth;
        this.height = this.container.offsetHeight;
        this.canvas.width = this.width;
        this.canvas.height = this.height;
    },

    setWeather: function(type) {
        this.stopLoop();
        this.clearSunny();
        this.particles = [];
        if (this.ctx) this.ctx.clearRect(0, 0, this.width, this.height);
        
        if (this.canvas) {
            this.canvas.style.pointerEvents = 'none';
            if (type === 'none') {
                this.canvas.classList.remove('active');
            } else {
                this.canvas.classList.add('active');
            }
        }

        this.currentType = type;
        localStorage.setItem('weather', type);

        // ★★★ 核心逻辑：控制乌云遮罩 ★★★
        const html = document.documentElement;
        if (type === 'rain' || type === 'snow') {
            html.classList.add('weather-overcast'); // 加上这个类，CSS 会显示灰色遮罩
        } else {
            html.classList.remove('weather-overcast');
        }

        switch (type) {
            case 'sunny':
                this.startSunny();
                break;
            case 'rain':
                this.initRain();
                this.startLoop();
                break;
            case 'snow':
                this.initSnow();
                this.startLoop();
                break;
            default: 
                break;
        }
    },

    // ===========================
    // 1. 晴天
    // ===========================
    startSunny: function() {
        // 创建光斑 DOM
        if (!this.container.querySelector('.sun-spot')) {
            const spot1 = document.createElement('div'); spot1.className = 'sun-spot spot-1';
            const spot2 = document.createElement('div'); spot2.className = 'sun-spot spot-2';
            const spot3 = document.createElement('div'); spot3.className = 'sun-spot spot-3';
            this.container.appendChild(spot1);
            this.container.appendChild(spot2);
            this.container.appendChild(spot3);
        }
    },

    clearSunny: function() {
        const spots = this.container.querySelectorAll('.sun-spot');
        spots.forEach(el => el.remove());
    },

    // ===========================
    // 2. 雪天
    // ===========================
    initSnow: function() {
        const count = window.innerWidth < 768 ? 40 : 80;
        for (let i = 0; i < count; i++) {
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                r: Math.random() * 2 + 1, 
                vy: Math.random() * 0.5 + 0.3, 
                swing: Math.random() * Math.PI * 2, 
                swingSpeed: Math.random() * 0.02 + 0.01
            });
        }
    },

    drawSnow: function() {
        this.ctx.clearRect(0, 0, this.width, this.height);
        this.ctx.fillStyle = "rgba(255, 255, 255, 0.9)"; // 雪花保持纯白
        this.ctx.beginPath();

        for (let i = 0; i < this.particles.length; i++) {
            let p = this.particles[i];
            this.ctx.moveTo(p.x, p.y);
            this.ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, true);

            p.y += p.vy;
            p.swing += p.swingSpeed;
            p.x += Math.sin(p.swing) * 0.5;

            if (p.x > this.width + 5 || p.x < -5 || p.y > this.height) {
                if (i % 3 > 0) { 
                    p.x = Math.random() * this.width;
                    p.y = -10;
                } else {
                    p.x = Math.random() > 0.5 ? -5 : this.width + 5;
                    p.y = Math.random() * this.height;
                }
            }
        }
        this.ctx.fill();
    },

    // ===========================
    // 3. 雨天 (颜色动态优化版)
    // ===========================
    initRain: function() {
        const count = window.innerWidth < 768 ? 60 : 120;
        for (let i = 0; i < count; i++) {
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                l: Math.random() * 20 + 10,  
                vx: -0.5 + Math.random() * 0.5, 
                vy: Math.random() * 10 + 15  
            });
        }
    },

    drawRain: function() {
        this.ctx.clearRect(0, 0, this.width, this.height);
        
        this.ctx.lineWidth = 1.5; // 线条不要太粗，否则生硬
        this.ctx.lineCap = 'round';
        
        // ★★★ 核心优化：根据当前模式决定雨滴颜色 ★★★
        // 获取 html 上的 data-bs-theme 属性
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

        if (isDark) {
            // 黑夜模式：白色半透明
            this.ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
        } else {
            // ★ 白天模式：改为灰蓝色！这样在浅色背景下看起来自然且清晰 ★
            this.ctx.strokeStyle = 'rgba(84, 107, 133, 0.5)'; 
        }
        
        this.ctx.beginPath();
        for (let i = 0; i < this.particles.length; i++) {
            let p = this.particles[i];
            
            this.ctx.moveTo(p.x, p.y);
            this.ctx.lineTo(p.x + p.vx, p.y + p.l);

            p.x += p.vx;
            p.y += p.vy;

            if (p.y > this.height) {
                p.x = Math.random() * this.width;
                p.y = -p.l - 10;
            }
        }
        this.ctx.stroke();
    },

    startLoop: function() {
        const loop = () => {
            if (this.currentType === 'rain') {
                this.drawRain();
                this.animationId = requestAnimationFrame(loop);
            } else if (this.currentType === 'snow') {
                this.drawSnow();
                this.animationId = requestAnimationFrame(loop);
            }
        };
        loop();
    },

    stopLoop: function() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
    },
    
    toggleNext: function() {
        const types = ['none', 'sunny', 'rain', 'snow'];
        let idx = types.indexOf(this.currentType);
        let nextIdx = (idx + 1) % types.length;
        this.setWeather(types[nextIdx]);
        return this.getCurrentIconClass();
    },
    
    getCurrentIconClass: function() {
        switch(this.currentType) {
            case 'none': return 'ti-cloud-off';
            case 'sunny': return 'ti-sun';
            case 'rain': return 'ti-cloud-rain';
            case 'snow': return 'ti-snowflake';
            default: return 'ti-cloud-off';
        }
    }
};

window.WeatherSystem = WeatherSystem;