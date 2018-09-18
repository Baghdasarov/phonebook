<template>
  <div class="hello">
    <div class="container">
      <h2>List</h2>
      <p>This is your phone-book. If you need to create new contact just click on "Create" button.</p>
      <router-link to="/create" class="btn btn-primary">Create</router-link>
      <input v-model="keyWord" placeholder="Search..." type="text" class="form-control search">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Numbers</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody v-if="start">
        <tr v-bind:key="contact.id" :ref="'id'+contact.id" :id="contact.id" v-for="contact in contacts">
          <td>{{contact.name}}</td>
          <td>
            <div v-bind:key="item.id" v-for="item in contact.contactNumbers">{{item.number}}</div>
            <b-btn v-b-modal.addNumber @click="currentContactId = contact.id; currentContactName = contact.name" class="btn btn-success"><font-awesome-icon icon="plus-circle" /></b-btn>
          </td>
          <td>
            <router-link :to="/edit/+contact.id" class="btn btn-primary"><font-awesome-icon icon="edit" /></router-link>
            <button @click="remove(contact.id)" class="btn btn-danger"><font-awesome-icon icon="trash-alt" /></button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div>
      <b-modal id="addNumber" @ok="addNumber" ref="addNumber" :title="'Add phone number to ' + currentContactName + ' contact'" >
        <div class="form-group">
          <label for="newNumber">New number</label>
          <input v-model="newNumber" type="number" maxlength="20" class="form-control" id="newNumber">
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
/*eslint no-console: ["error", { allow: ["warn", "error"] }] */
import Ajax from './../services/API/Ajax'
import bModal from 'bootstrap-vue/es/components/modal/modal'
import bBtn from 'bootstrap-vue/es/components/button/button'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEdit } from '@fortawesome/free-solid-svg-icons'
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons'
import { faPlusCircle } from '@fortawesome/free-solid-svg-icons'
library.add(faEdit)
library.add(faTrashAlt)
library.add(faPlusCircle)
export default {
	components: {
		bModal,
		bBtn
	},
  name: 'List',
  data() {
		return {
			contacts: {},
      start: false,
			currentContactId: '',
			currentContactName: '',
			newNumber: '',
			keyWord: ''
    }
  },
	watch: {
		'keyWord': function(val, oldVal){
			if (val.trim()!= '' && val.trim()!=oldVal.trim()) {
				this.search(val);
      } else if (val.trim()== '' && val.trim()!=oldVal.trim()) {
				this.getAllContacts()
      }
		}
	},
  created () {
    this.getAllContacts();
  },
  methods: {
		getAllContacts() {
			Ajax.getAllContacts()
				.then(response => {
					var contacts = response['data'];
					if(response.status) {
						this.manageContacts(contacts);
						this.start = true;
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
    addNumber() {
			const payload = {number: this.newNumber, id: this.currentContactId};
			Ajax.addNumber(payload)
				.then(response => {
					if (response.status) {
						if(this.contacts[this.currentContactId]['contactNumbers']){
							this.contacts[this.currentContactId]['contactNumbers'].push({ number: this.newNumber, id: response['data'] });
            } else {
              this.contacts[this.currentContactId]['contactNumbers'] = [];
              this.contacts[this.currentContactId]['contactNumbers'].push({ number: this.newNumber, id: response['data'] });
						}
						this.$refs.addNumber.hide();
						this.newNumber = '';
					} else {
						alert(response.message);
					}
				})
				.catch(error => {
					console.warn(error);
					alert('Oops. Something went wrong!');
				})
    },
    remove (id) {
      const payload = {id: id, name: 'contacts'};
      Ajax.delete(payload)
        .then(response => {
          if (response.status) {
            document.getElementById(id).innerHTML = '';
            this.getAllContacts();
          } else {
            alert(response.message);
          }
        })
        .catch(error => {
					console.warn(error);
					alert('Oops. Something went wrong!');
        })
    },
		search(keyWord) {
			const payload = {keyWord: keyWord}
			Ajax.search(payload)
				.then(response => {
					var contacts = response['data'];
					if(response.status) {
						this.manageContacts(contacts);
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
    manageContacts(contacts) {
			this.contacts = {};
			for (let i=0; i<contacts.length; i++) {
				if (this.contacts[contacts[i]['id']]) {
					this.contacts[contacts[i]['id']]['contactNumbers'].push({id: contacts[i]['number_id'], number: contacts[i]['number']});
				} else {
					this.contacts[contacts[i]['id']] = contacts[i];
					if (contacts[i]['number'] != '') {
						this.contacts[contacts[i]['id']]['contactNumbers'] = [];
						this.contacts[contacts[i]['id']]['contactNumbers'].push({id: contacts[i]['number_id'], number: contacts[i]['number']});
					}
					delete this.contacts[contacts[i]['id']]['number'];
					delete this.contacts[contacts[i]['id']]['number_id'];
				}
			}
    }
  }
}
</script>

<style scoped>
  table .btn {
    padding: 1px 6px;
    margin-right: 20px;
  }
  .search {
    margin: 1rem 0;
  }
</style>
