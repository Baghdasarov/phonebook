<template>
  <div class="hello">
    <div class="container">
      <h2 class="text-center">Edit</h2>
      <p class="text-center">Edit the name and click on "Update Name" button or add/remove numbers</p>
      <button @click="back" class="btn btn-primary"><font-awesome-icon icon="backward" /> Back</button>
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="name" type="text" class="form-control" id="name">
      </div>
      <div class="form-group">
        <label class="typo__label">Tagging</label>
        <multiselect v-model="numbers" placeholder="Add new phone number" label="phone" track-by="phone" :options=[] :multiple="true" :taggable="true" @tag="addTag" @remove="remove"></multiselect>
      </div>
      <button v-if="name=='' && numbers.length==0" @click="remove(null, 'contacts')" class="btn btn-danger">Remove Contact</button>
      <button v-else @click="update" class="btn btn-success" :disabled="(name==oldName)">Update Name</button>
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
				numbers: [],
				name: '',
        oldName: '',
        id: ''
			}
		},
    created() {
			this.id = this.$route.params.id;
			const payload = {id: this.id};
			Ajax.getContact(payload)
				.then(response => {
					if(response.status) {
						var contact = response['data'];
						this.name = contact[0]['name'];
						this.oldName = contact[0]['name'];
						if (contact[0]['number'] != '') {
							for (let i=0; i<contact.length; i++) {
								this.numbers.push({ phone: contact[i]['number'], id: contact[i]['number_id'] })
							}
						}
					} else {
						alert(response.message);
					}
				})
				.catch(error => {
					console.warn(error);
				})
    },
		methods: {
			getContact() {

      },
			addTag (newTag) {
        const payload = {number: newTag, id: this.id};
				Ajax.addNumber(payload)
					.then(response => {
						if(response.status) {
							this.numbers.push({ phone: newTag, id: this.id })
						} else {
							alert(response.message);
						}
					})
          .catch(error => {
            console.warn(error);
            alert('Oops. Something went wrong!');
          })
			},
      remove (removedOption, name) {
				name = name !== null ? name : 'numbers';
				const id = removedOption !== null ? removedOption['id'] : this.id;
				const payload = {id: id, name: name};
				Ajax.delete(payload)
					.then(response => {
						if(response.status) {
							if (this.name=='' && this.numbers.length==0) {
								this.back();
							}
						} else {
							alert(response.message);
						}
					})
					.catch(error => {
						console.warn(error);
					})
      },
      update() {
				const payload = {name: 'contacts', field: 'name', value: this.name, id: this.id};
				Ajax.update(payload)
					.then(response => {
						if(response.status) {
							alert(response.message);
						} else {
							alert(response.message);
						}
					})
					.catch(error => {
						console.warn(error);
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
