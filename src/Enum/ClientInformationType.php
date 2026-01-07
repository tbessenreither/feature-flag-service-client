<?php declare(strict_types=1);

namespace Tbessenreither\FeatureFlagServiceClient\Enum;


enum ClientInformationType: string
{
	case Information = 'information';
	case Warning = 'warning';
	case Error = 'error';

}