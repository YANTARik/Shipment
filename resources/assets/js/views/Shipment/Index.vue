<template>

	<div class="panel-body">

		<div class="shipment__item" v-for="shipment in shipments">
			<router-link class="shipment__inner" :to="`/shipments/${shipment.id}`">
				<p class="shipment__name">{{shipment.name}}</p>
			</router-link>
		</div>
		<div class="panel-footer" v-if="shipments.length">
        <span class="label label-default">You have {{ shipments.length }} Shipments </span>         
        </div>
	</div>
</template>
<script type="text/javascript">
	import { get } from '../../helpers/api'
	export default {
		data() {
			return {
				shipments: []
			}
		},
		created() {
			get('/api/shipments')
				.then((res) => {
					this.shipments = res.data.shipments
				})
		}
	}
</script>
