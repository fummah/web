import { Helmet } from 'react-helmet-async';

import { SwitchClaimsView } from 'src/sections/switch-claims/view';

// ----------------------------------------------------------------------

export default function SwitchClaimsPage() {
  return (
    <>
      <Helmet>
        <title> Request | MCA </title>
      </Helmet>

      <SwitchClaimsView />
    </>
  );
}
