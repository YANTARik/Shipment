<template>
	<div class="panel-body">
		<div class="shipment__header">
			<h3>{{action}} Shipment</h3>
			<div>
				<button class="btn btn__primary" @click="save" :disabled="isProcessing">Save</button>
				<button class="btn" @click="$router.back()" :disabled="isProcessing">Cancel</button>
			</div>
		</div>
		<div class="shipment__row">
			<div class="shipment__details">
				<div class="shipment__details_inner">
					<div class="form__group">
					    <label>Name</label>
					    <input type="text" class="form__control" v-model="form.name">
					    <small class="error__control" v-if="error.name">{{error.name[0]}}</small>
					</div>
				</div>
			</div>
		</div>
		<div class="shipment__row">
			<div class="shipment__items">
				<div class="shipment__box">
					<h3 class="shipment__sub_title">Items Shipment</h3>
					<div v-for="(item, index) in form.items" class="shipment__form">

						<input type="text" class="form__control form__qty" v-model="item.code"
							:class="[error[`items.${index}.code`] ? 'error__bg' : '']" placeholder="code item">

						<input type="text" class="form__control" v-model="item.name"
							:class="[error[`items.${index}.name`] ? 'error__bg' : '']" placeholder="name item">

						<input type="text" class="form__control form__qty" v-model="item.qty"
							:class="[error[`items.${index}.qty`] ? 'error__bg' : '']" placeholder="qty item">

						<button @click="remove('items', index)" class="btn btn__danger">&times;</button>
					</div>
					<button @click="addItem" class="btn btn-success pull-right">Add item</button>
				</div>
				
			</div>
			<div class="panel-footer" v-if="form.items.length">
        			<span class="label label-default">You have {{ form.items.length }} New Items </span> 
        	</div>	
		</div>
	</div>
</template>
<script type="text/javascript">
	import Vue from 'vue'
	import Flash from '../../helpers/flash'
	import { get, post } from '../../helpers/api'
	import { toMulipartedForm } from '../../helpers/form'
	export default {
		components: { },
		data() {
			return {
				form: {
					items: []
				},
				error: {},
				isProcessing: false,
				initializeURL: `/api/shipments/create`,
				storeURL: `/api/shipments`,
				action: 'Create'
			}
		},
		created() {
			if(this.$route.meta.mode === 'edit') {
				this.initializeURL = `/api/shipments/${this.$route.params.id}/edit`
				this.storeURL = `/api/shipments/${this.$route.params.id}?_method=PUT`
				this.action = 'Update'
			}
			get(this.initializeURL)
				.then((res) => {
					Vue.set(this.$data, 'form', res.data.form)
				})
		},
		methods: {
			save() {
				const form = toMulipartedForm(this.form, this.$route.meta.mode)
				post(this.storeURL, form)
				    .then((res) => {
				        if(res.data.saved) {
				            Flash.setSuccess(res.data.message)
				            this.$router.push(`/shipments/${res.data.id}`)
				        }
				        this.isProcessing = false
				    })
				    .catch((err) => {
				        if(err.response.status === 422) {
				            this.error = err.response.data
				        }
				        this.isProcessing = false
				    })
			},
			addItem() {
				this.form.items.push({
					code: '',
					name: '',
					qty: ''
				})
			},
			remove(type, index) {
				if(this.form[type].length > 1) {
					this.form[type].splice(index, 1)
				}
			}
		}
	}
</script>
