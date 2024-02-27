import { Helmet } from 'react-helmet-async';

import { QueryView } from 'src/sections/query/view';

// ----------------------------------------------------------------------

export default function QueryPage() {
  return (
    <>
      <Helmet>
        <title> Query | MCA </title>
      </Helmet>

      <QueryView />
    </>
  );
}
