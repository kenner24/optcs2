import { useSelector } from "react-redux";
import { Navigate, Outlet, useLocation } from "react-router-dom";
import { selectUserDetails } from "../redux/slices/UserProfileSlice";
import { PermissionConstant } from "../constants/permission.constant";

export default function AuthGuard() {
  const userDetails = useSelector(selectUserDetails);
  const location = useLocation();
  const permissionsArr = Array.isArray(userDetails?.assigned_permissions) ? userDetails?.assigned_permissions : [];
  const token = localStorage.getItem("token");
  const path = location.pathname;

  if (token?.length > 0) {
    if (checkPermission(permissionsArr, path)) {
      return <Outlet />;
    } else {
      return <Navigate to="/unauthorized" />
    }
  } else {
    return <Navigate to="/login" />;
  }
}

const checkPermission = (permissionsArr, currentPath) => {
  let hasAccess = false;
  switch (currentPath) {
  case "/reports":
  case "/marketing":
  case "/financial":
  case "/sales":
  case "/operations":
    hasAccess = permissionsArr?.includes(PermissionConstant.REPORTS);
    break;
  case "/connectors":
    hasAccess = permissionsArr?.includes(PermissionConstant.CONNECTORS);
    break;
  case "/staff":
    hasAccess = permissionsArr?.includes(PermissionConstant.SUB_ACCOUNT);
    break;
  case "/overview":
  case "/settings":
    hasAccess = true;
    break;
  default:
    hasAccess = false;
    break;
  }
  return hasAccess;
}