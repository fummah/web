import { Helmet } from 'react-helmet-async';

import { TipsView } from 'src/sections/tips/view';

// ----------------------------------------------------------------------

export default function TipsPage() {
  return (
    <>
      <Helmet>
        <title> Tips | MCA </title>
      </Helmet>

      <TipsView />
    </>
  );
}
