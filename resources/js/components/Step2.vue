<template>
  <div class="step-area">
    <div class="mb-9">
      <label for="selectMeal" class="form-label">Please select a restaurant</label>
      <select 
        class="form-select" 
        id="selectMeal" 
        aria-label="Default select example"
        v-model="state.restaurant"
      >
        <option v-for="(value,index) in listRestaurants" :key="index" :value="value">{{ value }}</option>
      </select>
      <span class="error-msg" v-if="v$.restaurant.$error">
        {{ v$.restaurant.$errors[0].$message }}
      </span>
    </div>
    <div class="d-flex justify-content-between">
      <button class="btn btn-outline-primary" @click="backStep">Previous</button>
      <button class="btn btn-outline-primary" @click="submitForm">Next</button>
    </div>
  </div>
</template>
<script>
import { mapActions, useStore } from "vuex";
import { reactive, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core'
import { required, helpers, numeric } from '@vuelidate/validators'
export default {
  name: "Step2",
  setup() {
    const currentStep = 2;
    const store = useStore();
    const state = reactive({
      restaurant: store.getters.stepTwo.restaurant ?? null,
    })

    const listRestaurants = computed(() => store.getters.listRestaurants);
    const rules = computed(() => {
      return {
        restaurant: { 
          required: helpers.withMessage('Meal is required', required),
        },
      }
    })

    const v$ = useVuelidate(rules, state);

    return {
      state,
      v$,
      store,
      listRestaurants,
      currentStep,
    }
  },
  methods: {
    ...mapActions({
      getListDishes: "getListDishes"
    }),
    backStep() {
      this.$emit('backStep', (this.currentStep));
    },
    submitForm() {
      this.v$.$validate()
      if (!this.v$.$error) {
        let data = {
          restaurant: this.state.restaurant,
          step: 2
        };
        this.$emit('stepTwoSuccessfully', data);
        this.store.dispatch("addStepTwo", data);
        let meal = this.store.getters.stepOne.meal;
        let restaurant = this.store.getters.stepTwo.restaurant;
        let params = {
          meal: meal,
          restaurant: restaurant
        };
        this.getListDishes({params: params});
      } else {
        console.log('failed');
      }
    }
  }
};
</script>
<style>
.mb-9 {
  margin-bottom: 36px;
}
</style>
