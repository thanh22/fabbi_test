<template>
  <div class="container">
    <header-vue ref="header" @changeStep="handleChangeStep" :curentStep="showStep"></header-vue>
    <step-1 
      v-if="showStep==1" 
      @stepOneSuccessfully="handleSuccessStep"
    ></step-1>
    <step-2 
      v-if="showStep==2" 
      @backStep="handleBackStep"
      @stepTwoSuccessfully="handleSuccessStep"
    ></step-2>
    <step-3 
      v-if="showStep==3"
      @backStep="handleBackStep"
      @stepThreeSuccessfully="handleSuccessStep"
    ></step-3>
    <review 
      v-if="showStep==4"
      @backStep="handleBackStep"
    ></review>
  </div>
</template>
<script>
import { ref } from "vue";
import HeaderVue from './Header.vue';
import Step1 from './Step1.vue';
import Step2 from './Step2.vue';
import Step3 from './Step3.vue';
import Review from './Review.vue';

export default {
  name: 'Home',
  components: {
    HeaderVue,
    Step1,
    Step2,
    Step3,
    Review,
  },
  data() {
    return {
      showStep: 1,
    }
  },
  methods: {
    handleChangeStep(ordinal) {
      this.showStep = ordinal;
    },
    handleSuccessStep(data) {
      this.$refs.header.clickStep(data.step + 1)
      this.showStep = data.step + 1;
    },
    handleBackStep(currentStep) {
      this.$refs.header.clickStep(currentStep - 1)
      this.showStep = currentStep - 1;
    }
  }
}
</script>
<style>
</style>
