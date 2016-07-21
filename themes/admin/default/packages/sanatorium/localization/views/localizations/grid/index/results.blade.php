<script type="text/template" data-grid="localization" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.locale %></td>
			<td><%= r.entity_id %></td>
			<td><%= r.entity_field %></td>
			<td><%= r.entity_type %></td>
			<td><%= r.entity_value %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
