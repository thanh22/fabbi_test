<template>
  <div class="step-area">
    <div class="row" v-for="(item,index) in state.dataStep3" :key="index">
      <div class="col-12 col-md-6">
        <div class="mb-9">
          <label for="selectMeal" class="form-label">Please select a dish</label>
          <select 
            class="form-select" 
            id="selectMeal" 
            aria-label="Default select example"
            v-model="item.dish"
            @change="checkSameDish(item)"
          >
            <option v-for="(value, indexDish) in listDishes" :key="indexDish" :value="value">{{ value }}</option>
          </select>
          <span class="error-msg" v-if="state.rowError && state.rowError == index">
            {{ 'data already exists, please choose another dish' }}
          </span>
          <span class="error-msg" v-if="state.rowErrorRequired.includes(index) && state.errDish">
            {{ 'field is required' }}
          </span>
        </div>
      </div>
      <div class="col-12 col-md-6 d-flex align-items-center">
        <div class="mb-9">
          <label for="inputNumber" class="form-label">Please enter no of servings</label>
          <input 
            v-model="item.noOfServing"
            type="number" 
            class="form-control" 
            min="0" 
            max="10" 
            id="inputNumber" 
            placeholder="">
          
        </div>
          <i 
            v-if="state.dataStep3.length >= 2"
            class="bi bi-x-circle"
            style="font-size: 35px;cursor: pointer; margin-left: 8px"
            @click="removeRow(item)"
          ></i>
      </div>
    </div>
    <div class="mb-9">
      <i 
        class="bi bi-plus-circle" 
        style="font-size: 35px;cursor: pointer"
        @click="addRow"
      ></i>
    </div>
    <div>
      <span class="error-msg" v-if="state.isSubmit && (state.totalDish > 10 || state.totalDish < numberOfPeople)">
        {{ 'The number of dishes is not satisfactory, please choose again' }}
      </span>
    </div>
    <div class="d-flex justify-content-between">
      <button class="btn btn-outline-primary" @click="backStep">Previous</button>
      <button class="btn btn-outline-primary" @click="submitForm">Next</button>
    </div>
  </div>
</template>
<script>
import { useStore } from "vuex";
import { reactive, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core'
import { required, helpers, numeric } from '@vuelidate/validators'
export default {
  name: "Step3",
  setup() {
    const currentStep = 3;
    const store = useStore();
    const state = reactive({
      isSubmit: false,
      totalDish: 0,
      errDish: false,
      errServing: false,
      rowError: null,
      rowErrorRequired: [],
      dataStep3: store.getters.stepThree.length ? store.getters.stepThree : [
        {
          dish: null,
          noOfServing: 1
        }
      ],
    })
    let numberOfPeople = computed(() => store.getters.stepOne.numberOfPeople);
    const listDishes = computed(() => store.getters.listDishes);

    return {
      state,
      listDishes,
      currentStep,
      numberOfPeople,
      store
    }
  },
  methods: {
    backStep() {
      this.$emit('backStep', (this.currentStep));
    },
    addRow() {
      this.state.dataStep3.push({
        dish: null,
        noOfServing: 1
      })
    },
    removeRow(item) {
      this.state.dataStep3.splice(this.state.dataStep3.indexOf(item), 1);
      if (this.state.dataStep3.length == 1) {
        this.state.rowError = null;
      } else {
        for (let index = 0; index < this.state.dataStep3.length; index++) {
          const element = this.state.dataStep3[index];
          for (let y = 1; y < this.state.dataStep3.length - 1; y++) {
            const el = this.state.dataStep3[y];
            if (element.dish == el.dish) {
              this.state.rowError = null;
            } 
          }
        }
      }
    },
    checkSameDish(item) {
      let count = 0;
      for (let index = 0; index < this.state.dataStep3.length; index++) {
        const element = this.state.dataStep3[index];
        if (element.dish == item.dish) {
          count += 1;
          this.state.rowErrorRequired.splice(this.state.dataStep3.indexOf(index), 1);
        }
        if (count > 1) {
          this.state.rowError = index;
        } else {
          this.state.rowError = null;
        }
      }
    },
    submitForm() {
      this.state.isSubmit = true;
      let total = 0;
      for (let index = 0; index < this.state.dataStep3.length; index++) {
        const element = this.state.dataStep3[index];
        if (!element.dish) {
          this.state.errDish = true;
          this.state.rowErrorRequired.push(index);
        }
        if (!element.noOfServing) {
          this.state.noOfServing = true
        }
        total += element.noOfServing;
      }

      this.state.totalDish = total;

      if (total < 10 && total >= this.numberOfPeople) {
        let data = {
          ...this.state.dataStep3,
          step: 3
        };
        this.$emit('stepThreeSuccessfully', data);
        this.store.dispatch("addStepThree", this.state.dataStep3); 
      }
    }
  }
};
</script>
<style scoped>
.mb-9 {
  margin-bottom: 36px;
}

.custom-btn-icon:focus,.custom-btn-icon:active {
   outline: none !important;
   box-shadow: none;
}
</style>
