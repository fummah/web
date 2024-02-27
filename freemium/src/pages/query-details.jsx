import { Helmet } from 'react-helmet-async';

import QueryDetails from 'src/sections/query/query-details';

// ----------------------------------------------------------------------

export default function QueryDetailsPage() {
  return (
    <>
      <Helmet>
        <title> Query | Details </title>
      </Helmet>

      <QueryDetails />
    </>
  );
}
