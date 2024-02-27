import { Helmet } from 'react-helmet-async';

import { ClaimsView } from 'src/sections/claims/view';

// ----------------------------------------------------------------------

export default function ClaimsPage() {
  return (
    <>
      <Helmet>
        <title> Claims | MCA </title>
      </Helmet>

      <ClaimsView />
    </>
  );
}
