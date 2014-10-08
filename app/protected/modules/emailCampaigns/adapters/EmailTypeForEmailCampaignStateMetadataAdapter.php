<?php
	/*********************************************************************************
	 * Zurmo EmailCampaigns is a custom module developed by Fireals Ltd.,
	 * and RIGHTS received by XGATE Corp. Ltd. Copyright (C) 2013 XGATE Corp. Ltd.
	 *
	 * Zurmo EmailCampaigns module is an enterprise plugin;
	 * you can NOT redistribute it and/or modify it without rights given by XGATE Corp. Ltd.
	 *
	 * Zurmo is distributed in the hope that it will be useful for XGATE services.
	 *
	 * You can contact XGATE Corp. Ltd. with a mailing address at Unit 107, 1/F.,
	 * Building 6, Bio-Informatics Centre No.2 Science Park West Avenue
	 * Hong Kong Science Park, Shatin, N.T., HK or at email address info@xgate.com.hk.
	 ********************************************************************************/

    /**
     * Adapter class to showing just Email Campaign List.
     */
    class EmailTypeForEmailCampaignStateMetadataAdapter extends StateMetadataAdapter
    {
        /**
         * Creates where clauses and adds structure information
         * to existing DataProvider metadata.
         */
        public function getAdaptedDataProviderMetadata()
        {
            $metadata      = $this->metadata;
            $clauseCount   = count($metadata['clauses']);
            $startingCount = $clauseCount + 1;
            $structure     = '';
            $metadata['clauses'][$startingCount] = array(
                'attributeName' => 'type',
                'operatorType'  => 'equals',
                'value'         => Campaign::EMAIL_CAMPAIGN
            );
            $structure    .= '(' . $startingCount . ')';
            if (empty($metadata['structure']))
            {
                $metadata['structure'] = '(' . $structure . ')';
            }
            else
            {
                $metadata['structure'] = '(' . $metadata['structure'] . ') and (' . $structure . ')';
            }
            return $metadata;
        }

        /**
         * Not Used
         * @return array|void
         * @throws NotImplementedException
         */
        protected function getStateIds()
        {
            throw new NotImplementedException();
        }
    }
?>