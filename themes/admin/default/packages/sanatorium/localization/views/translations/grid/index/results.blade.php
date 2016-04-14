<script type="text/template" data-grid="translations" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.namespace %></td>
			<td><%= r.locale %></td>
			<td><%= r.entity_id %></td>
			<td><%= r.entity_field %></td>
			<td><%= r.entity_value %></td>
		</tr>

	<% }); %>

</script>
