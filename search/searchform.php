<?php

function searchform() {
	echo "

<form method='post' action=''>
	<table class='container3' style='margin-left:auto;margin-right:auto'>
		<tr>
			<td>
				<table>
					<tr>
						<th colspan='2'>
							<font size='5'>URsTats Search</font>
						</th>
					</tr>
					<tr>
						<td>Search Type:</td>
						<td><select name='stype'>
								<option value='guid'>GUID</option>
								<option value='ip'>IP</option>
								<option value='name'>Name</option>
								<option value='laston'>Server</option>
								<option value='qport'>Qport</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Search:
						</td>
						<td><input type='text' name='sparm'>
						</td>
					</tr>
					<tr>
						<td colspan='2' style='text-align:center'>
							<input type='submit' class='nav' value='Search'>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>";

}
?>