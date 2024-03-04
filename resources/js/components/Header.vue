<template>
  <div>
    <ul class="nav nav-tabs justify-content-center mt-6">
      <li class="nav-item step-1">
        <a 
          class="nav-link"
          :class="{active: step == 1}"
          aria-current="page" 
          href="#"
        >Step 1</a>
      </li>
      <li class="nav-item step-2">
        <a 
          class="nav-link" 
          :class="{active: step == 2}"
          aria-current="page" 
          href="#"
        >Step 2</a>
      </li>
      <li class="nav-item step-3">
        <a 
          class="nav-link" 
          :class="{active: step == 3}"
          aria-current="page" 
          href="#"
        >Step 3</a>
      </li>
      <li class="nav-item step-4">
        <a 
          class="nav-link" 
          :class="{active: step == 4}"
          aria-current="page" 
          href="#"
        >Review</a>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  name: 'Header',
  components: {},
  props: {
    currentStep: { type: Number, default: 1 },
  },
  data() {
    return {
      isActive: false,
      step: 1,
    }
  },
  methods: {
    clickStep(ordinal) {
      this.step = ordinal;
      this.$emit('changeStep', ordinal);
    }
  },
  watch: {
    currentStep: {
      handler(newVal) {
        this.step = newVal;
      }
    }
  }
}
</script>
<style>
.mt-6 {
  margin-top: 24px;
}
.active {
  background: #1e57ad !important;
  color: #fff !important;
}
</style>