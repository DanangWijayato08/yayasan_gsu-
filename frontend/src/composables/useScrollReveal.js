import { onMounted, ref } from 'vue'

export function useScrollReveal(delay = 0) {
  const element = ref(null)

  onMounted(() => {
    if (!element.value) return

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            if (delay) {
              setTimeout(() => {
                entry.target.classList.add('is-visible')
              }, delay)
            } else {
              entry.target.classList.add('is-visible')
            }
            observer.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.15 }
    )

    observer.observe(element.value)
  })

  return { element }
}
