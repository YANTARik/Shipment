<template>
	<div class="shipment__show">
		<div class="shipment__row">
			<div class="shipment__details">
				<div class="shipment__details_inner">
					<small>Submitted by: {{ shipment.user.name }}</small>
					<h1 class="shipment__title">{{ shipment.name }}</h1>
					<div v-if="authState.api_token && authState.user_id === shipment.user_id">
						<router-link :to="`/shipments/${shipment.id}/edit`" class="btn btn-primary">
							Edit
						</router-link>
						<button class="btn btn__danger" @click="remove" :disabled="isRemoving">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<div class="shipment__row">
			<div class="shipment__items">
				<div class="shipment__box">
					<h3 class="shipment__sub_title">items</h3>
					<ul>
						<li v-for="(item, i) in shipment.items">
							<strong>Item {{i + 1}}: </strong>
							<span>{{item.code}}</span>
							<span>{{item.name}}</span>
							<span>{{item.qty}}</span>
						</li>
					</ul>
				</div>
				<div class="panel-footer" v-if="shipment.items.length">
		        <span class="label label-default">You have {{ shipment.items.length }} Items </span>         
		        </div>
				
			</div>
		</div>

	</div>
</template>
<script type="text/javascript">
	import Auth from '../../store/auth'
	import Flash from '../../helpers/flash'
	import { get, del } from '../../helpers/api'
	export default {
		data() {
			return {
				authState: Auth.state,
				isRemoving: false,
				shipment: {
					user: {},
					items: []
				}
			}
		},
		created() {
			get(`/api/shipments/${this.$route.params.id}`)
				.then((res) => {
					this.shipment = res.data.shipment
				})
		},

		methods: {
			remove() {
				this.isRemoving = false
				del(`/api/shipments/${this.$route.params.id}`)
					.then((res) => {
						if(res.data.deleted) {
							Flash.setSuccess('You have successfully deleted shipment!')
							this.$router.push('/')
						}
					})
			}
		}
	}
</script>
