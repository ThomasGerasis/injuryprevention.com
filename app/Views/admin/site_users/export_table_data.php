<table class="table datatable-basic dataTable no-footer table-hover table-bordered table-striped ci_datatable">
    <thead class="table-dark">
        <tr>
            <th></th>
            <th>Username</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Date added</th>
            <th>Active</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $i=>$item) { ?>
            <?php $username = htmlentities($item['username'], ENT_QUOTES, 'utf-8');
			$username = html_entity_decode($username, ENT_QUOTES , 'Windows-1252');?>
            <tr>
                <td><?php echo ($i+1); ?></td>
                <td><?php echo $username; ?></td>
                <td><?php echo $item['mobile_number']; ?></td>
                <td><?php echo $item['email']; ?></td>
                <td><?php echo date('d/m/Y H:i:s',strtotime($item['date_added'])); ?></td>
                <td><?php echo (empty($item['is_active']) ? 0 : 1);?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>