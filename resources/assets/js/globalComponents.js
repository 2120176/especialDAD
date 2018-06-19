

/**
 * You can register global components here and use them as a plugin in your main Vue instance
 */

const GlobalComponents = {
  install (Vue) {
    Vue.component('fg-input', () => import('./components/UIComponents/Inputs/formGroupInput.vue'))
    Vue.component('drop-down', () => import('./components/UIComponents/Dropdown.vue'))
  }
}

export default GlobalComponents
