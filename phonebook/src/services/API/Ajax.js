import axios from 'axios';

export default{
	getAllContacts() {
		return axios.get('/phonebooks/index')
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	getContact(payload) {
		return axios.post('/phonebooks/show', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	contactStore(payload) {
		return axios.post('/phonebooks/store', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	update(payload) {
		return axios.post('/phonebooks/update', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	addNumber(payload) {
		return axios.post('/phonebooks/addNumber', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	delete(payload) {
		return axios.post('/phonebooks/delete', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	},
	search(payload) {
		return axios.post('/phonebooks/search', payload)
			.then(response => {
				return response.data
			})
			.catch(function (error) {
				return error.response.data;
			});
	}
}