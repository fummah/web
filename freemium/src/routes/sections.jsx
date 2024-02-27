import { lazy, Suspense } from 'react';
import { Outlet, Navigate, useRoutes } from 'react-router-dom';

import DashboardLayout from 'src/layouts/dashboard';

export const IndexPage = lazy(() => import('src/pages/app'));
export const BlogPage = lazy(() => import('src/pages/blog'));
export const UserPage = lazy(() => import('src/pages/user'));
export const LoginPage = lazy(() => import('src/pages/login'));
export const SignupPage = lazy(() => import('src/pages/signup'));
export const VerifyEmailPage = lazy(() => import('src/pages/verify-email'));
export const FaqPage = lazy(() => import('src/pages/faq'));
export const QueryPage = lazy(() => import('src/pages/query'));
export const QueryDetailsPage = lazy(() => import('src/pages/query-details'));
export const SwitchClaimsPage = lazy(() => import('src/pages/switch-claims'));
export const TipsPage = lazy(() => import('src/pages/tips'));
export const ClaimsPage = lazy(() => import('src/pages/claims'));
export const ClaimDetailsPage = lazy(() => import('src/pages/claim-details'));
export const ProductsPage = lazy(() => import('src/pages/products'));
export const ProfilePage = lazy(() => import('src/pages/profile'));
export const Page404 = lazy(() => import('src/pages/page-not-found'));

// ----------------------------------------------------------------------

export default function Router() {
  const routes = useRoutes([
    {
      element: (

        <DashboardLayout>
                 <Suspense>
            <Outlet />
          </Suspense>
        </DashboardLayout>
      ),
      children: [
        { element: <IndexPage />, index: true },
        { path: 'user', element: <UserPage /> },
        { path: 'products', element: <ProductsPage /> },
        { path: 'blog', element: <BlogPage /> },
        { path: 'faq', element: <FaqPage /> },
        { path: 'query', element: <QueryPage /> },
        { path: 'query-details/:query_id', element: <QueryDetailsPage /> },
        { path: 'claim-details/:claim_id/:type', element: <ClaimDetailsPage /> },
        { path: 'switch-claims', element: <SwitchClaimsPage /> },
        { path: 'tips', element: <TipsPage /> },
        { path: 'claims', element: <ClaimsPage /> },
        { path: 'profile', element: <ProfilePage /> },
      ],
    },
    {
      path: 'login',
      element: <LoginPage />,
    },
    {
      path: 'signup',
      element: <SignupPage />,
    },
    {
      path: 'verify-email',
      element: <VerifyEmailPage />,
    },
    {
      path: '404',
      element: <Page404 />,
    },
    {
      path: '*',
      element: <Navigate to="/404" replace />,
    },
  ]);

  return routes;
}
