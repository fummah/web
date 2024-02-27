import { Navigate } from "react-router-dom";
import { Helmet } from 'react-helmet-async';

import { VerifyEmailView } from 'src/sections/verify-email';

// ----------------------------------------------------------------------

export default function VerifyEmailPage() {

  if (localStorage.getItem("UNACTIVATEDID") === null) {
  return <Navigate to="/signup"/>
}
  return (
    <>
      <Helmet>
        <title> Verify Email | MedClaim Assist </title>
      </Helmet>

      <VerifyEmailView />
    </>
  );
}
