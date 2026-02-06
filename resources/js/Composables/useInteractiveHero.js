import { ref, onMounted, onUnmounted, nextTick } from 'vue'

export function useInteractiveHero() {
    const mousePosition = ref({ x: 0, y: 0 })
    const isHovering = ref(false)
    const particles = ref([])
    const gradientPosition = ref({ x: 50, y: 50 })
    const textRevealProgress = ref(0)
    const magneticButtons = ref(new Map())
    
    let animationFrame = null
    let particleInterval = null
    let resizeObserver = null

    // Mouse tracking with smooth easing
    const handleMouseMove = (e) => {
        const { clientX, clientY } = e
        const { innerWidth, innerHeight } = window
        
        // Normalized mouse position (0-1)
        const normalizedX = clientX / innerWidth
        const normalizedY = clientY / innerHeight
        
        mousePosition.value = {
            x: clientX,
            y: clientY,
            normalizedX,
            normalizedY
        }
        
        // Update gradient position with smooth easing
        gradientPosition.value = {
            x: normalizedX * 100,
            y: normalizedY * 100
        }
        
        // Update magnetic buttons
        magneticButtons.value.forEach((button, element) => {
            updateMagneticButton(element, clientX, clientY)
        })
    }

    // Magnetic button effect
    const updateMagneticButton = (element, mouseX, mouseY) => {
        const rect = element.getBoundingClientRect()
        const centerX = rect.left + rect.width / 2
        const centerY = rect.top + rect.height / 2
        
        const deltaX = mouseX - centerX
        const deltaY = mouseY - centerY
        const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY)
        
        const maxDistance = 150
        const maxMove = 10
        
        if (distance < maxDistance) {
            const strength = 1 - (distance / maxDistance)
            const moveX = (deltaX / distance) * maxMove * strength
            const moveY = (deltaY / distance) * maxMove * strength
            
            element.style.transform = `translate(${moveX}px, ${moveY}px) scale(${1 + strength * 0.05})`
        } else {
            element.style.transform = 'translate(0, 0) scale(1)'
        }
    }

    // Register magnetic button
    const registerMagneticButton = (element) => {
        if (element && !magneticButtons.value.has(element)) {
            magneticButtons.value.set(element, true)
            element.style.transition = 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }
    }

    // Unregister magnetic button
    const unregisterMagneticButton = (element) => {
        if (magneticButtons.value.has(element)) {
            magneticButtons.value.delete(element)
            element.style.transform = ''
            element.style.transition = ''
        }
    }

    // Create floating particle
    const createParticle = () => {
        const particle = {
            id: Date.now() + Math.random(),
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            size: Math.random() * 4 + 1,
            speedX: (Math.random() - 0.5) * 0.5,
            speedY: (Math.random() - 0.5) * 0.5,
            opacity: Math.random() * 0.5 + 0.2,
            pulsePhase: Math.random() * Math.PI * 2
        }
        
        particles.value.push(particle)
        
        // Remove old particles to maintain performance
        if (particles.value.length > 50) {
            particles.value.shift()
        }
    }

    // Animate particles
    const animateParticles = () => {
        particles.value.forEach(particle => {
            particle.x += particle.speedX
            particle.y += particle.speedY
            particle.pulsePhase += 0.02
            
            // Wrap around edges
            if (particle.x < -10) particle.x = window.innerWidth + 10
            if (particle.x > window.innerWidth + 10) particle.x = -10
            if (particle.y < -10) particle.y = window.innerHeight + 10
            if (particle.y > window.innerHeight + 10) particle.y = -10
            
            // Mouse interaction
            const dx = mousePosition.value.x - particle.x
            const dy = mousePosition.value.y - particle.y
            const distance = Math.sqrt(dx * dx + dy * dy)
            
            if (distance < 100) {
                const force = (100 - distance) / 100
                particle.x -= (dx / distance) * force * 2
                particle.y -= (dy / distance) * force * 2
            }
        })
        
        animationFrame = requestAnimationFrame(animateParticles)
    }

    // Text reveal animation
    const startTextReveal = () => {
        const duration = 2000 // 2 seconds
        const startTime = Date.now()
        
        const reveal = () => {
            const elapsed = Date.now() - startTime
            const progress = Math.min(elapsed / duration, 1)
            
            // Easing function for smooth reveal
            const easeOutQuart = 1 - Math.pow(1 - progress, 4)
            textRevealProgress.value = easeOutQuart
            
            if (progress < 1) {
                requestAnimationFrame(reveal)
            }
        }
        
        requestAnimationFrame(reveal)
    }

    // Parallax effect for elements
    const getParallaxTransform = (element, intensity = 0.1) => {
        if (!element) {
            return {
                transform: 'translate(0, 0)',
                transition: 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
            }
        }
        
        const rect = element.getBoundingClientRect()
        const centerX = rect.left + rect.width / 2
        const centerY = rect.top + rect.height / 2
        
        const deltaX = (mousePosition.value.x - centerX) * intensity
        const deltaY = (mousePosition.value.y - centerY) * intensity
        
        return {
            transform: `translate(${deltaX}px, ${deltaY}px)`,
            transition: 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }
    }

    // Initialize
    onMounted(async () => {
        await nextTick()
        
        // Start particle system
        particleInterval = setInterval(createParticle, 200)
        animateParticles()
        
        // Start text reveal
        setTimeout(startTextReveal, 300)
        
        // Set up resize observer
        resizeObserver = new ResizeObserver(() => {
            // Adjust particles on resize
            particles.value = particles.value.filter(particle => 
                particle.x < window.innerWidth + 50 && 
                particle.y < window.innerHeight + 50
            )
        })
        
        resizeObserver.observe(document.body)
    })

    // Cleanup
    onUnmounted(() => {
        if (animationFrame) {
            cancelAnimationFrame(animationFrame)
        }
        if (particleInterval) {
            clearInterval(particleInterval)
        }
        if (resizeObserver) {
            resizeObserver.disconnect()
        }
        
        // Clean up magnetic buttons
        magneticButtons.value.forEach((_, element) => {
            unregisterMagneticButton(element)
        })
    })

    return {
        // Reactive state
        mousePosition,
        isHovering,
        particles,
        gradientPosition,
        textRevealProgress,
        
        // Methods
        handleMouseMove,
        registerMagneticButton,
        unregisterMagneticButton,
        getParallaxTransform,
        
        // Computed styles
        getGradientStyle: () => ({
            background: `radial-gradient(circle at ${gradientPosition.value.x}% ${gradientPosition.value.y}%, 
                        rgba(50, 202, 202, 0.15) 0%, 
                        transparent 50%)`,
            transition: 'background 0.3s ease'
        }),
        
        getParticleStyle: (particle) => ({
            position: 'absolute',
            left: `${particle.x}px`,
            top: `${particle.y}px`,
            width: `${particle.size}px`,
            height: `${particle.size}px`,
            backgroundColor: 'rgba(50, 202, 202, 0.6)',
            borderRadius: '50%',
            opacity: particle.opacity * (0.8 + Math.sin(particle.pulsePhase) * 0.2),
            pointerEvents: 'none',
            transition: 'opacity 0.3s ease'
        }),
        
        getTextRevealStyle: (delay = 0) => {
            const adjustedProgress = Math.max(0, Math.min(1, (textRevealProgress.value - delay) / (1 - delay)))
            return {
                opacity: adjustedProgress,
                transform: `translateY(${(1 - adjustedProgress) * 30}px)`,
                transition: 'none'
            }
        }
    }
}
