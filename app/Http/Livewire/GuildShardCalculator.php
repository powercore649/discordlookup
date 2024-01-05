<?php

namespace App\Http\Livewire;

use F9Web\Meta\Meta;
use Livewire\Component;

class GuildShardCalculator extends Component
{
    public $guildId;
    public $guildIdDisplay;
    public $totalShards;
    public $shardId;
    public $errorMessage;

    public function calculateShardId()
    {
        $this->resetExcept(['guildId', 'totalShards', 'guildIdDisplay']);

        if($this->guildId == null || $this->totalShards == null || $this->guildId == "-") return;

		$this->errorMessage = invalidateSnowflake($this->guildId);
        if (!validateInt($this->totalShards))
        {
            $this->errorMessage = __('Total Shards must be a valid number!');
        }

        if($this->errorMessage) return;

        $this->shardId = getShardId($this->guildId, $this->totalShards);
    }

    public function mount()
    {
		$this->guildIdDisplay = $this->guildId;

		if ($this->guildId == "-") {
			$this->guildIdDisplay = "";
		}

        Meta::set('title', __('Guild Shard ID Calculator'))
            ->set('og:title', __('Guild Shard ID Calculator'))
            ->set('description', __('Calculate the Shard ID of a guild using the Guild ID and the total number of shards.'))
            ->set('og:description', __('Calculate the Shard ID of a guild using the Guild ID and the total number of shards.'))
            ->set('keywords', 'bot shard, shards, calculator, shard calculator, shard id, guild id, ' . getDefaultKeywords());
	}

	public function updated($name, $value){
		if ($name == "guildIdDisplay") {
			$this->guildId = $value;
		}

		if($this->guildId == "" && $this->totalShards != "") {
			$this->guildId = "-";
		}

		if($this->guildId == "-" && $this->totalShards == "") {
			$this->guildId = "";
		}

		if ($this->guildId == "-") {
			$this->guildIdDisplay = "";
		}
	}

    public function render()
    {
        $this->calculateShardId();
        return view('guild-shard-calculator')->extends('layouts.app');
    }
}
