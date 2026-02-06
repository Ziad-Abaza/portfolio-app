import { ref, onMounted, onUnmounted, nextTick } from 'vue'

export function useSpecializedGeometrics() {
    const mousePosition = ref({ x: 0, y: 0 })
    const proficiencyElements = ref([])
    const expertiseElements = ref([])
    const hexagonGrid = ref([])
    const circularPattern = ref([])
    const isDarkMode = ref(false)
    
    let animationFrame = null
    let elementInterval = null
    let resizeObserver = null

    // Check dark mode
    const checkDarkMode = () => {
        isDarkMode.value = document.documentElement.classList.contains('dark')
    }

    // Get colors based on theme - matching portfolio design with enhanced light mode
    const getThemeColors = () => {
        return {
            primary: isDarkMode.value ? 'rgba(50, 202, 202, 0.5)' : 'rgba(50, 202, 202, 0.4)',
            secondary: isDarkMode.value ? 'rgba(46, 184, 184, 0.4)' : 'rgba(46, 184, 184, 0.3)',
            accent: isDarkMode.value ? 'rgba(106, 113, 129, 0.3)' : 'rgba(106, 113, 129, 0.2)',
            glow: isDarkMode.value ? 'rgba(50, 202, 202, 0.7)' : 'rgba(50, 202, 202, 0.6)',
            line: isDarkMode.value ? 'rgba(50, 202, 202, 0.15)' : 'rgba(50, 202, 202, 0.12)',
            // Enhanced cyan variations for better light mode visibility
            cyanLight: isDarkMode.value ? 'rgba(103, 232, 249, 0.5)' : 'rgba(103, 232, 249, 0.4)',
            cyanDark: isDarkMode.value ? 'rgba(34, 211, 238, 0.5)' : 'rgba(34, 211, 238, 0.35)',
            cyanAccent: isDarkMode.value ? 'rgba(6, 182, 212, 0.6)' : 'rgba(6, 182, 212, 0.5)',
            cyanVibrant: isDarkMode.value ? 'rgba(14, 165, 233, 0.6)' : 'rgba(14, 165, 233, 0.45)',
            cyanSoft: isDarkMode.value ? 'rgba(165, 243, 252, 0.5)' : 'rgba(165, 243, 252, 0.4)',
            cyanBright: isDarkMode.value ? 'rgba(224, 242, 254, 0.7)' : 'rgba(224, 242, 254, 0.6)'
        }
    }

    // Mouse tracking
    const handleMouseMove = (e) => {
        mousePosition.value = {
            x: e.clientX,
            y: e.clientY
        }
    }

    // Create hexagon grid for proficiency section
    const createHexagonGrid = () => {
        const colors = getThemeColors()
        const hexagon = {
            id: Date.now() + Math.random(),
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            size: Math.random() * 20 + 15,
            rotation: Math.random() * 360,
            speedX: (Math.random() - 0.5) * 0.2, // Slowed down from 0.5
            speedY: (Math.random() - 0.5) * 0.2, // Slowed down from 0.5
            rotationSpeed: (Math.random() - 0.5) * 0.3, // Slowed down from 1
            color: Math.random() > 0.5 ? colors.cyanVibrant : colors.cyanSoft,
            opacity: Math.random() * 0.3 + 0.15, // Reduced opacity
            pulsePhase: Math.random() * Math.PI * 2,
            scale: 1,
            type: 'hexagon'
        }
        
        hexagonGrid.value.push(hexagon)
        
        // Remove old elements
        if (hexagonGrid.value.length > 12) {
            hexagonGrid.value.shift()
        }
    }

    // Create circular pattern for expertise section
    const createCircularPattern = () => {
        const colors = getThemeColors()
        const circle = {
            id: Date.now() + Math.random(),
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            size: Math.random() * 25 + 10,
            rotation: Math.random() * 360,
            speedX: (Math.random() - 0.5) * 0.3, // Slowed down from 0.8
            speedY: (Math.random() - 0.5) * 0.3, // Slowed down from 0.8
            rotationSpeed: (Math.random() - 0.5) * 0.5, // Slowed down from 2
            color: Math.random() > 0.5 ? colors.cyanBright : colors.cyanLight,
            opacity: Math.random() * 0.4 + 0.2, // Reduced opacity
            pulsePhase: Math.random() * Math.PI * 2,
            scale: 1,
            type: 'circle'
        }
        
        circularPattern.value.push(circle)
        
        // Remove old elements
        if (circularPattern.value.length > 10) {
            circularPattern.value.shift()
        }
    }

    // Animate hexagon grid
    const animateHexagonGrid = () => {
        hexagonGrid.value.forEach(element => {
            // Update position
            element.x += element.speedX
            element.y += element.speedY
            element.rotation += element.rotationSpeed
            element.pulsePhase += 0.01 // Slowed down from 0.03
            
            // Wrap around edges
            if (element.x < -50) element.x = window.innerWidth + 50
            if (element.x > window.innerWidth + 50) element.x = -50
            if (element.y < -50) element.y = window.innerHeight + 50
            if (element.y > window.innerHeight + 50) element.y = -50
            
            // Mouse interaction - smoother, gentler repulsion for hexagons
            const dx = mousePosition.value.x - element.x
            const dy = mousePosition.value.y - element.y
            const distance = Math.sqrt(dx * dx + dy * dy)
            
            if (distance < 120) {
                const force = (120 - distance) / 120
                element.x -= (dx / distance) * force * 2 // Reduced from 4
                element.y -= (dy / distance) * force * 2 // Reduced from 4
                element.scale = 1 + force * 0.4 // Reduced from 0.8
                element.rotationSpeed += force * 0.5 // Reduced from 2
            } else {
                element.scale = 1
                element.rotationSpeed *= 0.99 // Smoother damping
            }
        })
    }

    // Animate circular pattern
    const animateCircularPattern = () => {
        circularPattern.value.forEach(element => {
            // Update position with circular motion
            element.x += element.speedX
            element.y += element.speedY
            element.rotation += element.rotationSpeed
            element.pulsePhase += 0.015 // Slowed down from 0.02
            
            // Add orbital motion - reduced amplitude
            const orbitRadius = 15 // Reduced from 30
            const orbitSpeed = 0.01 // Reduced from 0.02
            element.x += Math.cos(element.pulsePhase) * orbitRadius * 0.05 // Reduced multiplier
            element.y += Math.sin(element.pulsePhase) * orbitRadius * 0.05 // Reduced multiplier
            
            // Wrap around edges
            if (element.x < -50) element.x = window.innerWidth + 50
            if (element.x > window.innerWidth + 50) element.x = -50
            if (element.y < -50) element.y = window.innerHeight + 50
            if (element.y > window.innerHeight + 50) element.y = -50
            
            // Mouse interaction - gentler attraction for circles
            const dx = mousePosition.value.x - element.x
            const dy = mousePosition.value.y - element.y
            const distance = Math.sqrt(dx * dx + dy * dy)
            
            if (distance < 150) {
                const force = (150 - distance) / 150
                element.x += (dx / distance) * force * 1 // Reduced from 2
                element.y += (dy / distance) * force * 1 // Reduced from 2
                element.scale = 1 + force * 0.3 // Reduced from 0.6
            } else {
                element.scale = 1
            }
        })
    }

    // Get hexagon style
    const getHexagonStyle = (element) => {
        return {
            position: 'absolute',
            left: `${element.x}px`,
            top: `${element.y}px`,
            width: `${element.size}px`,
            height: `${element.size}px`,
            transform: `translate(-50%, -50%) rotate(${element.rotation}deg) scale(${element.scale})`,
            opacity: element.opacity * (0.8 + Math.sin(element.pulsePhase) * 0.2),
            pointerEvents: 'none',
            transition: 'transform 0.3s ease',
            zIndex: 1,
            backgroundColor: element.color,
            borderRadius: '20%',
            boxShadow: `0 0 20px ${element.color}`,
            clipPath: 'polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%)'
        }
    }

    // Get circle style
    const getCircleStyle = (element) => {
        return {
            position: 'absolute',
            left: `${element.x}px`,
            top: `${element.y}px`,
            width: `${element.size}px`,
            height: `${element.size}px`,
            transform: `translate(-50%, -50%) rotate(${element.rotation}deg) scale(${element.scale})`,
            opacity: element.opacity * (0.8 + Math.sin(element.pulsePhase) * 0.2),
            pointerEvents: 'none',
            transition: 'transform 0.3s ease',
            zIndex: 1,
            backgroundColor: element.color,
            borderRadius: '50%',
            boxShadow: `0 0 25px ${element.color}`,
            border: `2px solid ${element.color}`,
            background: `radial-gradient(circle, ${element.color} 0%, transparent 70%)`
        }
    }

    // Main animation loop
    const animate = () => {
        animateHexagonGrid()
        animateCircularPattern()
        animationFrame = requestAnimationFrame(animate)
    }

    // Initialize
    onMounted(async () => {
        await nextTick()
        
        // Check initial dark mode
        checkDarkMode()
        
        // Listen for dark mode changes
        const observer = new MutationObserver(checkDarkMode)
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        })
        
        // Start creating elements
        elementInterval = setInterval(() => {
            createHexagonGrid()
            createCircularPattern()
        }, 1200)
        
        // Create initial elements
        for (let i = 0; i < 6; i++) {
            createHexagonGrid()
            createCircularPattern()
        }
        
        // Start animation
        animate()
        
        // Set up resize observer
        resizeObserver = new ResizeObserver(() => {
            // Adjust elements on resize
            hexagonGrid.value = hexagonGrid.value.filter(element => 
                element.x < window.innerWidth + 100 && 
                element.y < window.innerHeight + 100
            )
            circularPattern.value = circularPattern.value.filter(element => 
                element.x < window.innerWidth + 100 && 
                element.y < window.innerHeight + 100
            )
        })
        
        resizeObserver.observe(document.body)
        
        // Cleanup observer on unmount
        return () => observer.disconnect()
    })

    // Cleanup
    onUnmounted(() => {
        if (animationFrame) {
            cancelAnimationFrame(animationFrame)
        }
        if (elementInterval) {
            clearInterval(elementInterval)
        }
        if (resizeObserver) {
            resizeObserver.disconnect()
        }
    })

    return {
        // Reactive state
        hexagonGrid,
        circularPattern,
        isDarkMode,
        
        // Methods
        handleMouseMove,
        getHexagonStyle,
        getCircleStyle,
        checkDarkMode
    }
}
