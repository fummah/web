import { Helmet } from 'react-helmet-async';

import { ResetPasswordView } from 'src/sections/reset-password';

// ----------------------------------------------------------------------

export default function ResetPasswordPage() {


  return (
    <>
      <Helmet>
        <title> Reset Password| MedClaim Assist </title>
      </Helmet>

      <ResetPasswordView />
    </>
  );
}
