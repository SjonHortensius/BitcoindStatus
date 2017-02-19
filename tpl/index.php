<div class="row marketing">
	<div class="col-lg-6">
		<h4>Node</h4>
		<div class="table-responsive">
			<table class="table table-striped">
				<tr><th>Node software</th><td><?=$this->info->network->subversion?></td></tr>
				<tr><th>Bitcoin / Protocol version</th><td><?=$this->info->version?> / <?=$this->info->protocolversion?></td></tr>
				<tr><th>Block height</th><td><?=$this->info->blocks?> [<?=sprintf('%.2d%%', (100/$this->blockCount)*$this->info->blocks)?>]</td></tr>
				<tr><th>Uptime</th><td>xx</td></tr>
				<tr><th>Disk usage</th><td>xx (xx free)</td></tr>
				<tr><th>Memory usage</th><td>xx (xx free)</td></tr>
			</table>
		</div>

		<h4>Network</h4>
		<div class="table-responsive">
			<table class="table table-striped">
				<tr><th>Online status (via <a href="https://21.co/">21.co</a>)</th><td><?=$this->onlineInfo->status?></td></tr>
				<tr><th>Connections</th><td><?=$this->info->connections?></td></tr>
				<?foreach ($this->info->network->networks as $network):?>
					<tr><th><?=$network->name?></th><td><?=$network->reachable?'true':'false'?></td></tr>
				<?endforeach?>
			</table>
		</div>

		<h4>Memory pool</h4>
		<div class="table-responsive">
			<table class="table table-striped">
				<tr><th># Transactions</th><td><?=$this->info->mempool->size?></td></tr>
				<tr><th>Size</th><td><?=BitcoindStatus::binaryPrefix($this->info->mempool->usage)?> (max <?=BitcoindStatus::binaryPrefix($this->info->mempool->maxmempool)?>)</td></tr>
			</table>
		</div>
	</div>
</div>
