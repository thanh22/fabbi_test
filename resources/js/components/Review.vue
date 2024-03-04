<template>
  <div class="step-area">
    <table v-if="!isSuccess" class="table table-striped">
      <tbody>
        <tr>
          <td>Meal</td>
          <td>{{meal}}</td>
        </tr>
        <tr>
          <td>No of People</td>
          <td>{{numberOfPeople}}</td>
        </tr>
        <tr>
          <td>Restaurant</td>
          <td>{{restaurant}}</td>
        </tr>
        <tr>
          <td>Dishes</td>
          <td>
            <div class="dish-list">
              <div v-for="value in dataStep3" :key="value">{{value.dish}} - {{value.noOfServing}}</div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="!isSuccess">
      <div class="d-flex justify-content-between">
        <button class="btn btn-outline-primary" @click="backStep">
          Previous
        </button>
        <button class="btn btn-outline-primary" @click="submitForm">
          Submit
        </button>
      </div>
    </div>
    <div v-if="isSuccess">
      <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Well done!</h4>
        <p>Congratulations, you have successfully created an order.</p>
      </div>
      <button class="btn btn-primary" @click="backToHome">Home</button>
    </div>
    
  </div>
</template>
<script>
import { mapActions, useStore } from "vuex";
import { computed, ref } from 'vue';
export default {
  setup() {
    const isSuccess = ref(false);
    const currentStep = 4;
    const store = useStore();
    let meal = computed(() => store.getters.stepOne.meal);
    let numberOfPeople = computed(() => store.getters.stepOne.numberOfPeople);
    let restaurant = computed(() => store.getters.stepTwo.restaurant);
    let dataStep3 = computed(() => store.getters.stepThree);

    return {
      store,
      meal,
      numberOfPeople,
      restaurant,
      dataStep3,
      currentStep,
      isSuccess
    }
  },
  methods: {
    ...mapActions({
      createOrder: "create"
    }),
    backStep() {
      this.$emit('backStep', (this.currentStep));
    },
    submitForm() {
      let params = {
        meal: this.meal,
        numberOfPeople: this.numberOfPeople,
        restaurant: this.restaurant,
        dataStep3: this.dataStep3
      };
      this.createOrder({
        params: params,
        successCallback: (res) => {
          this.isSuccess = true;
          console.log('this.isSuccess', this.isSuccess);
        },
        errCallback: err => {
          this.isSuccess = false;
        }
      });
    },
    backToHome() {
      this.$emit('backStep', 2);
      this.store.dispatch("resetState");
    }
  }
}
</script>
<style scoped>
.dish-list {
  border: 1px solid #333;
  padding: 8px;
}
</style>