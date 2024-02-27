import { Helmet } from 'react-helmet-async';

import ClaimDetails from 'src/sections/claims/claim-details';

// ----------------------------------------------------------------------

export default function ClaimDetailsPage() {
  return (
    <>
      <Helmet>
        <title> Claim | Details </title>
      </Helmet>

      <ClaimDetails />
    </>
  );
}
