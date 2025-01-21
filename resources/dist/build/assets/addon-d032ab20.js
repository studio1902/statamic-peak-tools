Statamic.booting(()=>{Statamic.$conditions.add("tracker_events",()=>{if(StatamicConfig.use_fathom||StatamicConfig.use_google)return!0})});
