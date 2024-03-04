<template>
    <div class="step-area">
      <div class="mb-3">
        <label for="selectMeal" class="form-label">Please select a meal</label>
          <select 
            class="form-select" 
            id="selectMeal" 
            aria-label="Default select example"
            v-model="state.meal"
          >
            <option v-for="value in listMeals" :key="value" :value="value">{{ value }}</option>
          </select>
          <span class="error-msg" v-if="v$.meal.$error">
            {{ v$.meal.$errors[0].$message }}
          </span>
      </div>
      <div class="mb-3">
        <label for="inputNumber" class="form-label">Please enter number of people</label>
          <input 
            v-model="state.numberOfPeople"
            type="number" 
            class="form-control" 
            min="0" 
            max="10" 
            id="inputNumber" 
            placeholder="">
          <span class="error-msg" v-if="v$.numberOfPeople.$error">
            {{ v$.numberOfPeople.$errors[0].$message }}
          </span>
      </div>
      <div class="d-flex justify-content-end">
        <button class="btn btn-primary" @click="submitForm">Next</button>
      </div>
    </div>
</template>
<script>
import { useVuelidate } from '@vuelidate/core'
import { required, maxValue, helpers, minValue, numeric } from '@vuelidate/validators'
import { reactive, computed } from 'vue'
import { useStore } from "vuex";
export default {
  setup() {
    const nextStep = 2;
    const store = useStore();
    const state = reactive({
      meal: store.getters.stepOne.meal ?? null,
      numberOfPeople: store.getters.stepOne.numberOfPeople ?? null,
    })
    store.dispatch("getListMeals");
    const listMeals = computed(() => store.getters.listMeals);

    const rules = computed(() => {
      return {
        meal: { 
          required: helpers.withMessage('Meal is required', required),
        },
        numberOfPeople: { 
          required: helpers.withMessage('Number of people is required', required), 
          maxValue: helpers.withMessage('Maximum number of people is 10', maxValue(10)),
          minValue: helpers.withMessage('Number of people must be greater than 0', minValue(0))
        }
      }
    })

    const v$ = useVuelidate(rules, state);

    return {
      nextStep,
      state,
      v$,
      store,
      listMeals,
    }
  },
  name: "Step1",
  methods: {
    submitForm() {
      this.v$.$validate()
      if (!this.v$.$error) {
        let data = {
          meal: this.state.meal,
          numberOfPeople: this.state.numberOfPeople,
          step: 1
        };
        this.$emit('stepOneSuccessfully', data);
        this.store.dispatch("addStepOne", data);
        this.store.dispatch("getListRestaurants", data.meal);
      } else {
        console.log('failed');
      }
    }
  }
};
</script>
<style>
.step-area {
  width: 50%;
  margin: 30px auto;
}
</style>
