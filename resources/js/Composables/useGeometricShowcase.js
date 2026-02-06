import { ref, onMounted, onUnmounted, nextTick } from 'vue'

export function useGeometricShowcase() {
    const mousePosition = ref({ x: 0, y: 0 })
    const geometricElements = ref([])
    const connectionLines = ref([])
    const ripples = ref([])
    const isDarkMode = ref(false)
    
    let animationFrame = null
    let elementInterval = null
    let resizeObserver = null

    // Check dark mode
    const checkDarkMode = () => {
        isDarkMode.value = document.documentElement.classList.contains('dark')
    }

    // Get colors based on theme
    const getThemeColors = () => {
        return {
            primary: isDarkMode.value ? 'rgba(50, 202, 202, 0.6)' : 'rgba(50, 202, 202, 0.4)',
            secondary: isDarkMode.value ? 'rgba(46, 184, 184, 0.4)' : 'rgba(46, 184, 184, 0.3)',
            accent: isDarkMode.value ? 'rgba(106, 113, 129, 0.3)' : 'rgba(106, 113, 129, 0.2)',
            glow: isDarkMode.value ? 'rgba(50, 202, 202, 0.8)' : 'rgba(50, 202, 202, 0.6)',
            line: isDarkMode.value ? 'rgba(50, 202, 202, 0.2)' : 'rgba(50, 202, 202, 0.15)'
        }
    }

    // Mouse tracking
    const handleMouseMove = (e) => {
        mousePosition.value = {
            x: e.clientX,
            y: e.clientY
        }
        
        // Create ripple effect at mouse position
        createRipple(e.clientX, e.clientY)
    }

    // Create ripple effect
    const createRipple = (x, y) => {
        const ripple = {
            id: Date.now() + Math.random(),
            x,
            y,
            size: 0,
            maxSize: 100,
            opacity: 0.6,
            color: getThemeColors().glow
        }
        
        ripples.value.push(ripple)
        
        // Animate ripple
        const animateRipple = () => {
            ripple.size += 3
            ripple.opacity -= 0.02
            
            if (ripple.opacity > 0) {
                requestAnimationFrame(animateRipple)
            } else {
                // Remove completed ripple
                const index = ripples.value.findIndex(r => r.id === ripple.id)
                if (index > -1) {
                    ripples.value.splice(index, 1)
                }
            }
        }
        
        requestAnimationFrame(animateRipple)
        
        // Limit ripples
        if (ripples.value.length > 5) {
            ripples.value.shift()
        }
    }

    // Create geometric element
    const createGeometricElement = () => {
        const types = ['circle', 'triangle', 'square', 'hexagon', 'diamond']
        const type = types[Math.floor(Math.random() * types.length)]
        const colors = getThemeColors()
        
        const element = {
            id: Date.now() + Math.random(),
            type,
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            size: Math.random() * 30 + 20,
            rotation: Math.random() * 360,
            speedX: (Math.random() - 0.5) * 1,
            speedY: (Math.random() - 0.5) * 1,
            rotationSpeed: (Math.random() - 0.5) * 2,
            color: Math.random() > 0.5 ? colors.primary : colors.secondary,
            opacity: Math.random() * 0.6 + 0.4,
            pulsePhase: Math.random() * Math.PI * 2,
            scale: 1
        }
        
        geometricElements.value.push(element)
        
        // Remove old elements
        if (geometricElements.value.length > 15) {
            geometricElements.value.shift()
        }
    }

    // Animate geometric elements
    const animateElements = () => {
        geometricElements.value.forEach(element => {
            // Update position
            element.x += element.speedX
            element.y += element.speedY
            element.rotation += element.rotationSpeed
            element.pulsePhase += 0.02
            
            // Wrap around edges
            if (element.x < -50) element.x = window.innerWidth + 50
            if (element.x > window.innerWidth + 50) element.x = -50
            if (element.y < -50) element.y = window.innerHeight + 50
            if (element.y > window.innerHeight + 50) element.y = -50
            
            // Mouse interaction
            const dx = mousePosition.value.x - element.x
            const dy = mousePosition.value.y - element.y
            const distance = Math.sqrt(dx * dx + dy * dy)
            
            if (distance < 150) {
                const force = (150 - distance) / 150
                element.x -= (dx / distance) * force * 3
                element.y -= (dy / distance) * force * 3
                element.scale = 1 + force * 0.5
            } else {
                element.scale = 1
            }
        })
        
        // Update connection lines
        updateConnectionLines()
        
        animationFrame = requestAnimationFrame(animateElements)
    }

    // Calculate connection lines between nearby elements
    const updateConnectionLines = () => {
        connectionLines.value = []
        const colors = getThemeColors()
        
        for (let i = 0; i < geometricElements.value.length; i++) {
            for (let j = i + 1; j < geometricElements.value.length; j++) {
                const elem1 = geometricElements.value[i]
                const elem2 = geometricElements.value[j]
                
                const distance = Math.sqrt(
                    Math.pow(elem1.x - elem2.x, 2) + 
                    Math.pow(elem1.y - elem2.y, 2)
                )
                
                if (distance < 200) {
                    connectionLines.value.push({
                        id: `${elem1.id}-${elem2.id}`,
                        x1: elem1.x,
                        y1: elem1.y,
                        x2: elem2.x,
                        y2: elem2.y,
                        opacity: (1 - distance / 200) * 0.3,
                        color: colors.line
                    })
                }
            }
        }
    }

    // Get element style based on type
    const getElementStyle = (element) => {
        const baseStyle = {
            position: 'absolute',
            left: `${element.x}px`,
            top: `${element.y}px`,
            width: `${element.size}px`,
            height: `${element.size}px`,
            transform: `translate(-50%, -50%) rotate(${element.rotation}deg) scale(${element.scale})`,
            opacity: element.opacity * (0.8 + Math.sin(element.pulsePhase) * 0.2),
            pointerEvents: 'none',
            transition: 'transform 0.3s ease',
            zIndex: 1
        }
        
        switch (element.type) {
            case 'circle':
                return {
                    ...baseStyle,
                    backgroundColor: element.color,
                    borderRadius: '50%',
                    boxShadow: `0 0 20px ${element.color}`
                }
            case 'square':
                return {
                    ...baseStyle,
                    backgroundColor: element.color,
                    borderRadius: '10%',
                    boxShadow: `0 0 20px ${element.color}`
                }
            case 'triangle':
                return {
                    ...baseStyle,
                    width: 0,
                    height: 0,
                    backgroundColor: 'transparent',
                    borderLeft: `${element.size/2}px solid transparent`,
                    borderRight: `${element.size/2}px solid transparent`,
                    borderBottom: `${element.size}px solid ${element.color}`,
                    filter: `drop-shadow(0 0 10px ${element.color})`
                }
            case 'hexagon':
                return {
                    ...baseStyle,
                    backgroundColor: element.color,
                    borderRadius: '20%',
                    boxShadow: `0 0 20px ${element.color}`,
                    clipPath: 'polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%)'
                }
            case 'diamond':
                return {
                    ...baseStyle,
                    backgroundColor: element.color,
                    borderRadius: '10%',
                    boxShadow: `0 0 20px ${element.color}`,
                    transform: `translate(-50%, -50%) rotate(45deg) scale(${element.scale})`
                }
            default:
                return baseStyle
        }
    }

    // Get line style
    const getLineStyle = (line) => ({
        position: 'absolute',
        left: `${line.x1}px`,
        top: `${line.y1}px`,
        width: `${Math.sqrt(Math.pow(line.x2 - line.x1, 2) + Math.pow(line.y2 - line.y1, 2))}px`,
        height: '1px',
        backgroundColor: line.color,
        opacity: line.opacity,
        transform: `rotate(${Math.atan2(line.y2 - line.y1, line.x2 - line.x1) * 180 / Math.PI}deg)`,
        transformOrigin: '0 50%',
        pointerEvents: 'none',
        zIndex: 0
    })

    // Get ripple style
    const getRippleStyle = (ripple) => ({
        position: 'absolute',
        left: `${ripple.x}px`,
        top: `${ripple.y}px`,
        width: `${ripple.size}px`,
        height: `${ripple.size}px`,
        borderRadius: '50%',
        border: `2px solid ${ripple.color}`,
        opacity: ripple.opacity,
        transform: 'translate(-50%, -50%)',
        pointerEvents: 'none',
        zIndex: 2
    })

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
        elementInterval = setInterval(createGeometricElement, 800)
        
        // Create initial elements
        for (let i = 0; i < 8; i++) {
            createGeometricElement()
        }
        
        // Start animation
        animateElements()
        
        // Set up resize observer
        resizeObserver = new ResizeObserver(() => {
            // Adjust elements on resize
            geometricElements.value = geometricElements.value.filter(element => 
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
        geometricElements,
        connectionLines,
        ripples,
        isDarkMode,
        
        // Methods
        handleMouseMove,
        getElementStyle,
        getLineStyle,
        getRippleStyle,
        checkDarkMode
    }
}
