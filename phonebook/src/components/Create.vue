<template>
  <div class="hello">
    <div class="container">
      <h2 class="text-center">Create</h2>
      <p class="text-center">Fill in the form and click on "Create" button</p>
      <button @click="back" class="btn btn-primary"><font-awesome-icon icon="backward" /> Back</button>
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="name" type="text" class="form-control" id="name">
      </div>
      <div class="form-group">
        <label class="typo__label">Tagging</label>
        <multiselect v-model="value" placeholder="Add new phone number" label="phone" track-by="phone" :options=[] :multiple="true" :taggable="true" @tag="addTag"></multiselect>
      </div>
      <button @click="store" class="btn btn-primary" :disabled="!(name!='' || value.length!=0)">Create</button>
    </div>
  </div>
</template>

<script>
/*eslint no-console: ["error", { allow: ["warn", "error"] }] */
import Multiselect from 'vue-multiselect'
import Ajax from './../services/API/Ajax'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faBackward } from '@fortawesome/free-solid-svg-icons'
library.add(faBackward)

export default {
	components: {
		Multiselect
	},
  name: 'Create',
	data () {
		return {
			value: [],
      name: ''
		}
	},
	methods: {
		addTag (newTag) {
			const tag = {
				phone: newTag,
			};
			this.value.push(tag)
		},
    store() {
			const payload = {};
			payload['name'] = this.name;
			payload['numbers'] = [];
			for (var i=0; i<this.value.length; i++) {
				payload['numbers'].push(this.value[i]['phone']);
      }
			Ajax.contactStore(payload)
        .then(response => {
					if(response.status) {
						this.back()
					} else {
						alert(response.message);
						this.contacts = {};
					}
				})
        .catch(error => {
					console.warn(error);
					alert('Oops. Something went wrong!');
				})
    },
		back() {
			this.$router.push({name: 'list'})
		}
	}
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
